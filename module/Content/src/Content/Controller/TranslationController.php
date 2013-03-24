<?php
/**
 * This source file is part of GotCms.
 *
 * GotCms is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * GotCms is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License along
 * with GotCms. If not, see <http://www.gnu.org/licenses/lgpl-3.0.html>.
 *
 * PHP Version >=5.3
 *
 * @category   Gc_Application
 * @package    Content
 * @subpackage Controller
 * @author     Pierre Rambaud (GoT) <pierre.rambaud86@gmail.com>
 * @license    GNU/LGPL http://www.gnu.org/licenses/lgpl-3.0.html
 * @link       http://www.got-cms.com
 */

namespace Content\Controller;

use Gc\Mvc\Controller\Action;
use Gc\Document\Collection as DocumentCollection;
use Content\Form;
use Gc\Component;
use Gc\Core\Translator;
use Zend\Json\Json;

/**
 * Translation controller
 *
 * @category   Gc_Application
 * @package    Content
 * @subpackage Controller
 */
class TranslationController extends Action
{
    /**
     * Contains information about acl
     *
     * @var array $_aclPage
     */
    protected $aclPage = array('resource' => 'Content', 'permission' => 'translation');

    /**
     * Initialize Media Controller
     *
     * @return void
     */
    public function init()
    {
        $documents = new DocumentCollection();
        $documents->load(0);

        $this->layout()->setVariable('treeview', Component\TreeView::render(array($documents)));

        $routes = array(
            'edit' => 'documentEdit',
            'new' => 'documentCreate',
            'delete' => 'documentDelete',
            'copy' => 'documentCopy',
            'cut' => 'documentCut',
            'paste' => 'documentPaste',
            'refresh' => 'documentRefreshTreeview',
        );

        $array_routes = array();
        foreach ($routes as $key => $route) {
            $array_routes[$key] = $this->url()->fromRoute($route, array('id' => 'itemId'));
        }

        $this->layout()->setVariable('routes', Json::encode($array_routes));
    }

    /**
     * Create Translation
     *
     * @return \Zend\View\Model\ViewModel|array
     */
    public function createAction()
    {
        $translation_form = new Form\Translation();
        $translation_form->setAttribute('action', $this->url()->fromRoute('translationCreate'));

        if ($this->getRequest()->isPost()) {
            $post = $this->getRequest()->getPost();
            $translation_form->setData($post->toArray());
            if (!$translation_form->isValid()) {
                $this->flashMessenger()->addErrorMessage('Invalid data sent !');
                $this->useFlashMessenger();
            } else {
                $source = $post->get('source');
                $data   = array();
                foreach ($post->get('destination') as $destination_id => $destination) {
                    $data[$destination_id] = array('value' => $destination);
                }

                foreach ($post->get('locale') as $locale_id => $locale) {
                    if (empty($data[$locale_id])) {
                        continue;
                    }

                    $data[$locale_id]['locale'] = $locale;
                }

                $this->flashMessenger()->addSuccessMessage('Translation saved !');
                Translator::setValue($source, $data);
                return $this->redirect()->toRoute('translationCreate');
            }
        }

        return array('form' => $translation_form);
    }

    /**
     * List and edit translation
     *
     * @return \Zend\View\Model\ViewModel|array
     */
    public function indexAction()
    {
        $translation_form = new Form\Translation();
        $translation_form->setAttribute('action', $this->url()->fromRoute('translationList'));
        if ($this->getRequest()->isPost()) {
            $post = $this->getRequest()->getPost();
            if (empty($post['source']) or empty($post['destination'])) {
                return $this->redirect()->toRoute('translationList');
            }

            foreach ($post['source'] as $source_id => $source) {
                Translator::getInstance()->update(array('source' => $source), sprintf('id = %d', $source_id));
                if (!empty($post['destination'][$source_id])) {
                    Translator::setValue($source_id, $post['destination'][$source_id]);
                }
            }

            $this->generateCache();

            $this->flashMessenger()->addSuccessMessage('Translation saved !');
            return $this->redirect()->toRoute('translationList');
        }

        return array('form' => $translation_form, 'values' => Translator::getValues());
    }

    /**
     * Generate php array file as cache
     *
     * @return void
     */
    protected function generateCache()
    {
        $values = Translator::getValues();
        $data   = array();
        foreach ($values as $value) {
            if (empty($data[$value['locale']])) {
                $data[$value['locale']] = array();
            }

            $data[$value['locale']][$value['source']] = $value['destination'];
        }

        $translate_path   = GC_APPLICATION_PATH . '/data/translation/%s.php';
        $template_content = file_get_contents(GC_APPLICATION_PATH . '/data/install/tpl/language.tpl.php');

        foreach (glob(sprintf($translate_path, '*')) as $file) {
            unlink($file);
        }

        foreach ($data as $locale => $values) {
            file_put_contents(sprintf($translate_path, $locale), sprintf($template_content, var_export($values, true)));
        }
    }
}
