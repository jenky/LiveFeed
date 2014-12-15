!function($, window, document, _undefined) 
{    
    $.tools.tooltip.addEffect('LiveFeedPreview',
    function(callback)
    {
        var triggerOffset = this.getTrigger().offset(),
            config = this.getConf(),
            css = {
                /*top: 'auto',
                bottom: $(window).height() - triggerOffset.top + config.offset[0]*/
                top: triggerOffset.top + config.offset[0],
                bottom: 'auto'
            },
            narrowScreen = ($(window).width() < 480);

        /*if (XenForo.isRTL())
        {
            css.right = $('html').width() - this.getTrigger().outerWidth() - triggerOffset.left - config.offset[1];
            css.left = 'auto';
        }
        else
        {
            css.left = triggerOffset.left + config.offset[1];
            if (narrowScreen)
            {
                css.left = Math.min(50, css.left);
            }
        }*/

        this.getTip().css(css).xfFadeIn(XenForo.speed.normal);

    },
    function(callback)
    {
        this.getTip().xfFadeOut(XenForo.speed.fast);
    });

    /**
     * Cache to store fetched previews
     *
     * @var object
     */
    XenForo._LiveFeedPreviewCache = {};

    XenForo.FeedPreview = function($el)
    {
        var hasTooltip, previewUrl, setupTimer;

        if (!parseInt(XenForo._enableOverlays))
        {
            return;
        }

        if (!(previewUrl = $el.data('feedpreviewurl')))
        {
            console.warn('Preview tooltip has no preview: %o', $el);
            return;
        }

        $el.find('[title]').andSelf().attr('title', '');

        $el.bind(
        {
            mouseenter: function(e)
            {
                if (hasTooltip)
                {
                    return;
                }

                setupTimer = setTimeout(function()
                {

                    if (hasTooltip)
                    {
                        return;
                    }

                    hasTooltip = true;

                    var $tipSource = $('#PreviewTooltip'),
                        $tipHtml,
                        xhr;

                    if (!$tipSource.length)
                    {
                        console.error('Unable to find #PreviewTooltip');
                        return;
                    }

                    $tipHtml = $tipSource.clone()
                        .removeAttr('id')
                        .addClass('LiveFeedPreviewTooltip')
                        .appendTo(document.body);

                    if (!XenForo._LiveFeedPreviewCache[previewUrl])
                    {
                        xhr = XenForo.ajax(
                            previewUrl,
                            {},
                            function(ajaxData)
                            {
                                if (XenForo.hasTemplateHtml(ajaxData))
                                {
                                    XenForo._LiveFeedPreviewCache[previewUrl] = ajaxData.templateHtml;

                                    $(ajaxData.templateHtml).xfInsert('replaceAll', $tipHtml.find('.PreviewContents'));
                                }
                                else
                                {
                                    $tipHtml.remove();
                                }
                            },
                            {
                                type: 'GET',
                                error: false,
                                global: false
                            }
                        );
                    }

                    $el.tooltip(XenForo.configureTooltipRtl({
                        /*predelay: 500,
                        delay: 0,*/
                        effect: 'LiveFeedPreview',
                        fadeInSpeed: 'slow',
                        fadeOutSpeed: 'slow',
                        tip: $tipHtml,
                        position: 'bottom left',
                        offset: [ 0, -10 ]
                    }));

                    $el.data('tooltip').show(0);

                    if (XenForo._LiveFeedPreviewCache[previewUrl])
                    {
                        $(XenForo._LiveFeedPreviewCache[previewUrl])
                            .xfInsert('replaceAll', $tipHtml.find('.PreviewContents'), 'show', 0);
                    }
                }, 800);
            },

            mouseleave: function(e)
            {
                /*if (hasTooltip)
                {
                    if ($el.data('tooltip'))
                    {
                        $el.data('tooltip').hide();
                    }

                    return;
                }*/
                if (setupTimer)
                {
                    clearTimeout(setupTimer);
                }
            },

            mousedown: function(e)
            {
                // the click will cancel a timer or hide the tooltip
                if (setupTimer)
                {
                    clearTimeout(setupTimer);
                }

                if ($el.data('tooltip'))
                {
                    $el.data('tooltip').hide();
                }
            }
        });
    }

    /* no minify */
    XenForo.register('.NewsFeedItem', 'XenForo.FeedPreview');
}
(jQuery, this, document);