<?php

class Content_DocumentController extends Es_Controller_Action
{
    public function init()
    {
        $documents = new Es_Model_DbTable_Document_Collection();
        $documents->load(0);
        $this->_helper->layout->assign('treeview', Es_Component_TreeView::render(array($documents)));

        $router = $this->getFrontController()->getRouter();
        $routes = array(
            'edit' => 'documentEdit'
            , 'new' => 'documentAdd'
            , 'delete' => 'documentDelete'
            , 'copy' => 'documentCopy'
            , 'cut' => 'documentCut'
            , 'paste' => 'documentPaste'
        );

        $array_routes = array();
        foreach($routes as $key => $route)
        {
            if($router->hasRoute($route))
            {
                $array_routes[$key] = $router->assemble(array('id' => 'itemId'), $route);
            }
        }

        $this->_helper->layout->assign('routes', Zend_Json::encode($array_routes));
    }

    public function addAction()
    {
        $document_form = new Content_Form_DocumentAdd();
        $document_form->setAction($this->getFrontController()->getRouter()->assemble(array(), 'documentAdd'));

        if($this->getRequest()->isPost())
        {
            if(!$document_form->isValid($this->getRequest()->getPost()))
            {
                $this->_helper->flashMessenger->setNameSpace('error')->addMessage('Invalid document data');
            }
            else
            {
                $document_name = $document_form->getValue('name');
                $document_url_key = $document_form->getValue('url_key');
                $document_type_id = $document_form->getValue('document_type');
                $parent_id = $this->getRequest()->getPost('parent_id');
                $document = new Es_Model_DbTable_Document_Model();
                $document->setName($document_name)
                    ->setDocumentTypeId($document_type_id)
                    ->setParentId($parent_id)
                    ->setUrlKey(!empty($document_url_key) ? $document_url_key : $this->_checkUrlKey($document_name));

                $document_id = $document->save();
                if(empty($document_id))
                {
                    $this->_helper->flashMessenger->setNameSpace('error')->addMessage('Can not add document');
                }
                else
                {
                    $this->_helper->flashMessenger->setNameSpace('success')->addMessage('Document successfuly add');
                    $this->_helper->redirector->goToRoute(array('id' => $document_id), 'documentEdit');
                }
            }
        }

        $this->view->form = $document_form;

    }

    public function deleteAction()
    {
        $document = Es_Model_DbTable_Document_Model::fromId($this->getRequest()->getParam('id', ''));
        if(empty($document))
        {
            $this->_helper->flashMessenger->setNameSpace('error')->addMessage('Document does not exists !');
        }
        else
        {
            try
            {
                if($document->delete())
                {
                    $this->_helper->flashMessenger->setNameSpace('success')->addMessage('This document was succefully delete');
                }
                else
                {
                    $this->_helper->flashMessenger->setNameSpace('success')->addMessage('There were problems during the removal of this document');
                }
            }
            catch (Exception $e)
            {
                Es_Error::set(get_class($this), $e);
            }
        }

        return $this->_helper->redirector->goToRoute(array(), 'conent');
    }

    public function editAction()
    {
        $document = Es_Model_DbTable_Document_Model::fromId($this->getRequest()->getParam('id', ''));
        $document_form = new Zend_Form();
        if(empty($document))
        {
            $this->_helper->flashMessenger->setNameSpace('error')->addMessage('Document does not exists !');
        }
        else
        {
            $document_type_id = $document->getDocumentTypeId();
            $layout_id = $this->getRequest()->getParam('layout_id', '');

            if($this->getRequest()->isPost())
            {
                $has_error = FALSE;
                $document_vars = $this->getRequest()->getPost('document');

                $document->setName(empty($document_vars['name']) ? $document->getName() : $document_vars['name']);
                $document->setStatus(empty($document_vars['status']) ? FALSE : $document_vars['status']);
                $document->showInNav(empty($document_vars['show_in_nav']) ? FALSE : $document_vars['show_in_nav']);
                $document->setLayoutId(empty($document_vars['layout']) ? FALSE : $document_vars['layout']);
                $document->setViewId(empty($document_vars['view']) ? $document->getViewId() : $document_vars['view']);
                $document->setUrlKey(empty($document_vars['url_key']) ? $document->getUrlKey() : $document_vars['url_key']);
            }

            $tabs = $this->_loadTabs($document_type_id);
            $tabs_array = array();
            $datatypes = array();

            $i = 1;
            foreach($tabs as $tab)
            {
                $tabs_array[] = $tab->getName();
                $properties = $this->_loadProperties($document_type_id, $tab->getId(), $document->getId());
                $sub_form = new Zend_Form_SubForm();
                $sub_form->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'dl','id' => 'tabs-'.$i))));
                $sub_form->removeDecorator('Fieldset');
                $sub_form->removeDecorator('DtDdWrapper');
                $sub_form->setIsArray(FALSE);
                foreach($properties as $property)
                {
                    $property->setDocumentId($document->getId())->loadValue();
                    if($this->getRequest()->isPost())
                    {
                        if(!Es_Model_DbTable_Datatype_Model::saveEditor($property, $document))
                        {
                            $has_error = TRUE;
                        }
                    }

                    Es_Form::addContent($sub_form, Es_Model_DbTable_Datatype_Model::loadEditor($property, $document));
                }

                $document_form->addSubForm($sub_form, 'tabs-'.$i, $i);
                $i++;
            }

            $tabs_array[] = 'Document information';

            $form_document_add = new Content_Form_DocumentAdd();
            $form_document_add->load($document, $i);

            $document_form->addSubForm($form_document_add, 'tabs-'.$i, $i);

            $submit = new Zend_Form_Element_Submit('submit-form');
            $submit->setLabel('Save');
            $document_form->addElement($submit);

            if($this->getRequest()->isPost())
            {
                if($has_error)
                {
                    $document->showInNav(FALSE);
                    $document->setStatus(FALSE);
                    $this->_helper->flashMessenger->setNameSpace('error')->addMessage('This document cannot be published and show in nav because one or more properties are required !');

                }

                $document->save();
                $this->_helper->redirector->goToRoute(array('id' => $document->getId()), 'documentEdit');
            }

            $this->view->tabs = new Es_Component_Tabs($tabs_array);
            $this->view->form = $document_form;
        }
    }

    protected function _checkUrlKey($string)
    {
        $replace = array(' ', 'àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ');
        $to = array('-', 'aaaaaceeeeiiiinooooouuuuyyaaaaaceeeeiiiinooooouuuuy');
        $string = strtolower(str_replace($replace, $to, trim($string)));

        return $string;
    }

    protected function _loadTabs($document_type_id)
    {
        $document_type = Es_Model_DbTable_DocumentType_Model::fromId($document_type_id);
        $tabs = $document_type->getTabs();

        return $tabs;
    }

    protected function _loadProperties($document_type_id, $tab_id, $document_id)
    {
        $properties = new Es_Model_DbTable_Property_Collection();
        $properties->load($document_type_id, $tab_id, $document_id);

        return $properties->getProperties();
    }
}