<?php

namespace App\Controllers;

use App\Models\NewsModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\ResponseInterface;

class News2 extends BaseController
{
    private NewsModel $model;

    /**
     * Validation Rules
     */
    private array $rules = [
        'title' => 'required|min_length[3]|max_length[255]',
        'body'  => 'required|min_length[10]|max_length[5000]',
    ];

    public function __construct()
    {
        $this->model = model(NewsModel::class);
    }

    public function getIndex(): string
    {
        $data = [
            'news'  => $this->model->getNews(),
            'title' => 'News archive',
        ];

        $data['controller'] = strtolower(class_basename(__CLASS__));

        return view('templates/header', $data)
            . view('news/overview2', $data)
            . view('templates/footer', $data);
    }

    public function getView($slug = null): string
    {
        $data['news'] = $this->model->getNews($slug);

        if (empty($data['news'])) {
            throw new PageNotFoundException('Cannot find the news item: ' . $slug);
        }

        $data['title']      = $data['news']['title'];
        $data['controller'] = strtolower(class_basename(__CLASS__));

        return view('templates/header', $data)
            . view('news/view', $data)
            . view('templates/footer', $data);
    }

    public function getCreate(): string
    {
        helper('form');

        $data['controller'] = strtolower(class_basename(__CLASS__));

        return view('templates/header', ['title' => 'Create a news item'])
            . view('news/create', $data)
            . view('templates/footer');
    }

    public function postCreate(): string
    {
        if ($this->validate($this->rules)) {
            $this->model->save([
                'title' => $this->request->getPost('title'),
                'slug'  => url_title($this->request->getPost('title'), '-', true),
                'body'  => $this->request->getPost('body'),
            ]);

            return view('news/success');
        }

        return $this->getCreate();
    }

    public function getEdit($id = ''): string
    {
        helper('form');

        if ($id === '') {
            throw new PageNotFoundException('Cannot find the news item: ' . $id);
        }

        $data['news'] = $this->model->find($id);

        if (empty($data['news'])) {
            throw new PageNotFoundException('Cannot find the news item: ' . $id);
        }

        $data['controller'] = strtolower(class_basename(__CLASS__));

        return view('templates/header', ['title' => 'Edit news item'])
            . view('news/edit2', $data)
            . view('templates/footer');
    }

    /**
     * @param false|int $id
     *
     * @return ResponseInterface|string
     */
    public function postUpdate($id = false)
    {
        if ($this->validate($this->rules)) {
            $title = $this->request->getVar('title');
            $slug  = url_title($title, '-', true);

            $data = [
                'title' => $title,
                'slug'  => $slug,
                'body'  => $this->request->getVar('body'),
            ];
            $this->model->update($id, $data);

            return $this->response->redirect(site_url('news2/view/' . $slug));
        }

        return $this->getEdit($id);
    }
}
