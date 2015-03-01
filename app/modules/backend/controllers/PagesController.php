<?php

/**
 * Pages controller, url: /admin/pages/
 *
 * @category 
 * @package phalconskeleton
 * @author Tim Marshall
 * @copyright (c) 2015, Tim Marshall
 * @version 
 */

namespace backend\controllers;

class PagesController extends ControllerBase
{

    /**
     * Display all pages
     */
    public function indexAction()
    {
        $this->tag->appendTitle("Pages");
        $this->view->pages = \Pages::find();
    }

    /**
     * Create a new page
     */
    public function newAction()
    {
        $this->tag->appendTitle("New Page");

        $form = $this->form;
        $form->title = 'New Page';
        $form->submitButton = 'Save';
        $form->cancelHref = 'admin/pages';

        $form
        ->addField(new \Forms\Fields\Textbox([
            'key' => 'name',
            'label' => 'Name',
            'required' => true,
            'size' => 6
        ]))
        ->addField(new \Forms\Fields\Switchbox([
            'key' => 'standalone',
            'label' => 'Standalone page',
            'toggleRequired' => ['url'],
            'size' => 6
        ]))
        ->addField(new \Forms\Fields\Textbox([
            'key' => 'url',
            'label' => 'Url',
            'required' => function ()
            {
                return ($this->request->getPost('standalone', 'trim') == 'on') ? true : false;
            },
            'size' => 12
        ]));

        if ($form->validate()) {
            $page = $form->addToModel(new \Pages());

            if ($page->save()) {
                $this->auth->redirect('admin/pages', 'success', 'Page created.');
            }
        }
        $form->render();
    }

    /**
     * Edit a page
     * @param int $page_id
     */
    public function editAction($page_id)
    {
        $this->tag->appendTitle("Edit Page");
        $page = \Pages::findFirstById($page_id);
        if (!$page) {
            $this->auth->redirect('admin/pages', 'error', 'Invalid Page ID.');
        }
        $form = $this->form;
        $form->title = 'Edit Page: ' . $page->name;
        $form->submitButton = 'Save';
        $form->cancelHref = 'admin/pages';

        $form
        ->addField(new \Forms\Fields\Textbox([
            'key' => 'name',
            'label' => 'Name',
            'required' => true,
            'default' => $page->name,
            'size' => 6
        ]))
        ->addField(new \Forms\Fields\Switchbox([
            'key' => 'standalone',
            'label' => 'Standalone page',
            'toggleRequired' => ['url'],
            'default' => $page->standalone,
            'size' => 6
        ]))
        ->addField(new \Forms\Fields\Textbox([
            'key' => 'url',
            'label' => 'Url',
            'default' => $page->url,
            'required' => function () use ($page)
            {
                if ($this->request->isPost()) {
                    return ($this->request->getPost('standalone', 'trim') == 'on') ? true : false;
                }
                return $page->standalone;
            },
            'size' => 12
        ]));

        if ($form->validate()) {
            $page = $form->addToModel($page);

            if ($page->save()) {
                $this->auth->redirect('admin/pages', 'success', 'Page updated.');
            }
        }
        $form->render();
    }

    /**
     * Manage a page
     * @param int $page_id
     */
    public function manageAction($page_id)
    {
        $this->tag->appendTitle("Manage Page");
        $page = \Pages::findFirstById($page_id);
        if (!$page) {
            $this->auth->redirect('admin/pages', 'error', 'Invalid Page ID.');
        }
        $this->view->page = $page;
        $form = $this->form;
        $form->title = '';
        $form->submitButton = 'Add';
        $form->cancelHref = 'admin/pages';

        $form
        ->addField(
        new \Forms\Fields\Select([
            'key' => 'offset',
            'label' => 'Left Offset',
            'class' => 'browser-default',
            'options' => function ()
            {
                $options = [];
                foreach (range(0, 12) as $value) {
                    $options[] = ['value' => $value, 'label' => $value, 'default' => false];
                }
                return $options;
            },
            'size' => 6
        ]))
        ->addField(
        new \Forms\Fields\Select([
            'key' => 'width',
            'label' => 'Width',
            'class' => 'browser-default',
            'options' => function ()
            {
                $options = [];
                foreach (range(12, 1) as $value) {
                    $options[] = ['value' => $value, 'label' => $value, 'default' => false];
                }
                return $options;
            },
            'size' => 6
        ]))
        ->addField(new \Forms\Fields\Hidden(['key' => 'parent_id', 'default' => null]));

        if ($form->validate()) {
            $content = $form->addToModel(new \Contents());
            $content->page_id = $page->id;
            $content->content = 'Edit Me';
            $content->save();
            $this->auth->redirect('admin/pages/manage/' . $page->id, 'success', 'Section Added.');
        }
        $form->render();
    }

    /**
     * Reorder the contents of a page (via ajax)
     * @param int $page_id
     */
    public function reorderAction($page_id)
    {
        $page = \Pages::findFirstById($page_id);
        if ($page) {
            foreach ($this->request->getPost('ordering') as $order => $content_id) {
                $content = \Contents::findFirstById($content_id);
                if ($content) {
                    $content->ordering = $order;
                    $content->save();
                }
            }
        }
    }

    /**
     * Remove the parent_id from a piece of content
     * @param int $content_id
     */
    public function emancipateAction($content_id)
    {
        $content = \Contents::findFirstById($content_id);
        if ($content) {
            $content->parent_id = null;
            $content->save();
            $this->auth->redirect('admin/pages/manage/' . $content->pages->id, 'success', 'Section Emancipated.');
        }
    }
    
    /**
     * Edit contents
     * @param int $content_id
     */
    public function contentAction($content_id)
    {
        $this->tag->appendTitle("Edit Content");
        $content = \Contents::findFirstById($content_id);
        if (!$content) {
            $this->auth->redirect('admin/pages', 'error', 'Invalid Page ID.');
        }
        $this->view->contents = $content;
        
        $form = $this->form;
        $form->title = 'Edit Content: #' . $content->id;
        $form->submitButton = 'Save';
        $form->cancelHref = 'admin/pages/manage/' . $content->pages->id;
        
        $form
        ->addField(new \Forms\Fields\Textarea([
            'key' => 'content',
            'label' => 'Content',
            'default' => $content->content
        ]));
        
        if ($form->validate()) {
            $content = $form->addToModel($content);
            $content->save();
            $this->auth->redirect('admin/pages/manage/' . $content->pages->id, 'success', 'Content Edited.');
        }
        $form->render();
    }

    /**
     * Delete a content
     * @param int $content_id
     */
    public function deletecontentAction($content_id)
    {
        $content = \Contents::findFirstById($content_id);
        if (!$content) {
            $this->auth->redirect('admin/pages', 'error', 'Invalid Page ID.');
        }
        $page = $content->pages;
        $content->delete();
        $this->auth->redirect('admin/pages/manage/' . $page->id, 'success', 'Section Deleted.');
    }

}
