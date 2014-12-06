<?php

class Milano_LiveFeed_DataWriter_Option extends XFCP_Milano_LiveFeed_DataWriter_Option
{
    protected function _postSaveAfterTransaction()
    {
        parent::_postSaveAfterTransaction();

        if ($this->get('option_id') == 'LiveFeed_widget')
        {
            XenForo_Application::getOptions()->set('LiveFeed_widget', unserialize($this->get('option_value')));
            $this->_repaseTemplate();
        }
    }

    protected function _repaseTemplate()
    {
        $templateModel = $this->getModelFromCache('XenForo_Model_Template');
        $templateModificationModel = $this->getModelFromCache('XenForo_Model_TemplateModification');

        $templates = $templateModel->getTemplatesByTitles(array('forum_list'));
        foreach ($templates AS $template)
        {
            $content = $template['template'] ? $template['template'] : '';
            $templateModificationModel->applyModificationsToTemplate('forum_list', $template['template'], $status);

            $templateModel->reparseTemplate($template, true);
        }
    }
}