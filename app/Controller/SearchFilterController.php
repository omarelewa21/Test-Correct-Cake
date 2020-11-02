<?php

App::uses('AppController', 'Controller');
App::uses('SearchFilterService', 'Lib/Services');

/**
 * Schools controller
 *
 */
class SearchFilterController extends AppController
{
    /**
     * Called before each action.
     *
     * @return void
     */
    public function beforeFilter()
    {
        $this->SearchFilterService = new SearchFilterService();

        parent::beforeFilter();
    }

    /**
     * add new search filter for user
     * test in console: jQuery.post('/searchfilter/add',{data:{search_filter:{name:"test filter van GM",key:"itembank_toetsen",filters:'{"type":"bla bla"}'}}})
     */
    public function add()
    {
        if ($this->request->is('post')) {
            $searchFilter = $this->request->data['search_filter'];
            $result = $this->SearchFilterService->add($searchFilter);

            $this->formResponse(!empty($result), $result);
        }
    }

    /**
     *  edit searchfilter by uuid
     *  test in console: $.ajax({
     * url: '/searchfilter/edit/b08a5f3c-b3c4-48ce-9699-394dd01fdc41',
     * type: 'PUT',
     * data: {search_filter:{name:"test filter van GM",key:"itembank_toetsen",filters:'{"type":"bla bla bla bla"}'}}
     * });
     */

    public function edit($uuid)
    {
        $this->autoRender = false;
        if ($this->request->is('put')) {
            $searchFilter = $this->request->data['search_filter'];
            $result = $this->SearchFilterService->edit($uuid, $searchFilter);
            $this->formResponse(!empty($result), $result);
        }
    }

    /**
     *  delete searchfilter by uuid
     *  test in console: $.ajax({
     * url: '/searchfilter/delete/{uuid}',
     * type: 'DELETE',
     * });
     */

    public function delete($uuid)
    {
        $this->autoRender = false;
        if ($this->request->is('delete')) {
            $result = $this->SearchFilterService->delete($uuid);
            $this->formResponse(!empty($result), $result);
        }
    }


    /**
     *  get searchfilter by key
     *  test in console: $.ajax({
     *                  url: '/searchfilter/get/itembank_toetsen',
     *                  type: 'GET',
     *                });
     */
    public function get($key)
    {
        $result = $this->SearchFilterService->get($key);
        $this->formResponse(!empty($result), $result);
    }

    public function activate($uuid)
    {
        $this->autoRender = false;
        $result = $this->SearchFilterService->activate($uuid);
        $this->formResponse(!empty($result), $result);
    }
}
