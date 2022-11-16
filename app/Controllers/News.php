<?php

namespace App\Controllers;

use App\Models\NewsModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\ResponseInterface;

class News extends BaseController
{
    private NewsModel $model;

    public function __construct()
    {
        $this->model = model(NewsModel::class);
    }

    public function index(): string
    {
        $data = [
            'news'  => $this->model->getNews(),
            'title' => 'News archive',
        ];

        $data['controller'] = strtolower(class_basename(__CLASS__));

        return view('templates/header', $data)
            . view('news/overview', $data)
            . view('templates/footer', $data);
    }

    public function view($slug = null): string
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

    public function create(): string
    {
        helper('form');

        if ($this->request->getMethod() === 'post' && $this->validate([
            'title' => 'required|min_length[3]|max_length[255]',
            'body'  => 'required|min_length[10]|max_length[5000]',
        ])) {
            $this->model->save([
                'title' => $this->request->getPost('title'),
                'slug'  => url_title($this->request->getPost('title'), '-', true),
                'body'  => $this->request->getPost('body'),
            ]);

            return view('news/success');
        }

        $data['controller'] = strtolower(class_basename(__CLASS__));

        return view('templates/header', ['title' => 'Create a news item'])
            . view('news/create', $data)
            . view('templates/footer');
    }

    public function edit($id = ''): string
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
            . view('news/edit', $data)
            . view('templates/footer');
    }

    /**
     * @return ResponseInterface|string
     */
    public function update()
    {
        $id = $this->request->getPost('id');

        if ($this->request->getMethod() === 'post' && $this->validate([
            'title' => 'required|min_length[3]|max_length[255]',
            'body'  => 'required|min_length[10]|max_length[5000]',
        ])) {
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

        return $this->edit($id);
    }
}
