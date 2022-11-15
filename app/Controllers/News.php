<?php

namespace App\Controllers;

use App\Models\NewsModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class News extends BaseController
{
    private NewsModel $model;

    public function __construct()
    {
        $this->model = model(NewsModel::class);
    }

    public function index()
    {
        $data = [
            'news'  => $this->model->getNews(),
            'title' => 'News archive',
        ];

        return view('templates/header', $data)
            . view('news/overview', $data)
            . view('templates/footer', $data);
    }

    public function view($slug = null)
    {
        $data['news'] = $this->model->getNews($slug);

        if (empty($data['news'])) {
            throw new PageNotFoundException('Cannot find the news item: ' . $slug);
        }

        $data['title'] = $data['news']['title'];

        return view('templates/header', $data)
            . view('news/view', $data)
            . view('templates/footer', $data);
    }

    public function create()
    {
        helper('form');

        if ($this->request->getMethod() === 'post' && $this->validate([
            'title' => 'required|min_length[3]|max_length[255]',
            'body'  => 'required',
        ])) {
            $this->model->save([
                'title' => $this->request->getPost('title'),
                'slug'  => url_title($this->request->getPost('title'), '-', true),
                'body'  => $this->request->getPost('body'),
            ]);

            return view('news/success');
        }

        return view('templates/header', ['title' => 'Create a news item'])
            . view('news/create')
            . view('templates/footer');
    }

    public function edit($id = '')
    {
        helper('form');

        if ($id === '') {
            throw new PageNotFoundException('Cannot find the news item: ' . $id);
        }

        $data['news'] = $this->model->find($id);

        if (empty($data['news'])) {
            throw new PageNotFoundException('Cannot find the news item: ' . $id);
        }

        return view('templates/header', ['title' => 'Edit news item'])
            . view('news/edit', $data)
            . view('templates/footer');
    }

    public function update()
    {
        if ($this->request->getMethod() === 'post' && $this->validate([
            'title' => 'required|min_length[3]|max_length[255]',
            'body'  => 'required',
        ])) {
            $id    = $this->request->getPost('id');
            $title = $this->request->getPost('title');
            $slug  = url_title($title, '-', true);

            $data = [
                'title' => $title,
                'slug'  => $slug,
                'body'  => $this->request->getPost('body'),
            ];
            $this->model->update($id, $data);

            return $this->response->redirect(site_url('news/' . $slug));
        }

        return view('templates/header', ['title' => 'Edit news item'])
            . view('news/edit')
            . view('templates/footer');
    }
}
