<?php

namespace App\Admin\Controllers;

use App\Models\Pages;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
class PagesController extends AdminController
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    protected $title = '页面';
    public function index(Content $content)
    {
        return $content
            ->header(trans('页面'))
            ->description(trans('列表'))
            ->body($this->grid());
    }

    /**
     * Show interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header(trans('admin.detail'))
            ->description(trans('admin.description'))
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header(trans('页面'))
            ->description(trans('编辑'))
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header(trans('页面'))
            ->description(trans('创建'))
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Pages);

        $grid->column('title', __('标题'));
        //$grid->column('content', __('内容'))->label();
        $grid->column('tag', __('链接'))->display(function ($tag) {
            return url("pages/$tag.html");
        })->link();
        $grid->column('status', __('状态'))->editable('select', [1 => '启用', 2 => '关闭']);
        $grid->created_at(trans('admin.created_at'));
        $grid->updated_at(trans('admin.updated_at'));
        
        $grid->actions(function ($actions) {
            $actions->disableView();
        });
        return $grid;
    }


    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Pages);

        
        $form->text('title', '标题')->required();;
        $form->UEditor('content', '内容')->required();
        $form->text('tag', '标识')->required()->help('页面链接为：'.url("").'/pages/标识.html');
        $form->radio('status', __('状态'))->options([1=> '启用', 2=> '关闭'])
            ->rules('required',['请选择类型'])
            ->default(1);
            $form->footer(function ($footer) {
            // 去掉`查看`checkbox
        $footer->disableViewCheck();
        });
        $form->tools(function (Form\Tools $tools) {
            // 去掉`查看`按钮
            $tools->disableView();
        });
        return $form;
    }
}