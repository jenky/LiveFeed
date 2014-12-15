!function($, window, document, _undefined) 
{
    XenForo.LiveFeed = function($element) { this.__construct($element); };
    XenForo.LiveFeed.prototype =
    {
        __construct: function($element)
        {
            this.$element = $element;
            $(document).bind('XFAjaxSuccess', $.context(this, 'update'));
            $(document).bind('XenForoWindowFocus', $.context(this, 'focus'));

            this.interval = parseInt(this.$element.data('pollinginterval'), 10) || 30;
            this.xhr = null;
            this.activate(true);
        },

        focus: function(e)
        {
            if (!this.active)
            {
                this.activate(true);
            }
        },

        activate: function(instant)
        {
            if (instant)
            {
                this.update();
            }

            return this.active = window.setInterval($.context(this, 'update'), this.interval * 1000);
        },

        loadNewsFeed: function()
        {
            if (this.xhr === null)
            {
                this.xhr = XenForo.ajax(
                    'index.php?recent-activity/',
                    { live_feed: this.$element.data('newestitemid'), referer: this.$element.attr('href') },
                    $.context(this, 'display'),
                    { error: false, global: false }
                );
            }
            return false;
        },

        display: function(ajaxData, textStatus)
        {
            this.xhr = null;

            if (XenForo.hasResponseError(ajaxData))
            {
                return;
            }

            if (ajaxData.newestItemId)
            {
                if (this.$element.data('newestitemid') >= ajaxData.newestItemId)
                {
                    return;
                }

                this.$element.data('newestitemid', ajaxData.newestItemId);
            }

            if (XenForo.hasTemplateHtml(ajaxData))
            {
                var $html = $(ajaxData.templateHtml),
                    $noContent = $('#newsFeedEmpty');

                if ($html.length)
                {
                    if ($noContent.length)
                    {
                        $noContent.empty().remove();
                    }
                    //$html.find('.event:first').addClass('forceBorder');
                    $html.xfInsert('prependTo', '.newsFeed:first');
                }
            }
        },

        update: function(force)
        {
            if (!XenForo._hasFocus && !force)
            {
                return this.deactivate();
            }

            this.loadNewsFeed();
        },

        deactivate: function()
        {
            window.clearInterval(this.active);
            this.active = false;
        },
    }

    XenForo.register('.NewsFeedLoader', 'XenForo.LiveFeed');
}
(jQuery, this, document);