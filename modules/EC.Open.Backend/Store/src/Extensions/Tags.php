<?php

namespace GuoJiangClub\EC\Open\Backend\Store\Extensions;

use Encore\Admin\Form\Field;

class Tags extends Field
{
	protected $view = 'store-backend::extensions.tags';

	protected static $css = [
		'/assets/backend/libs/Tagator/fm.tagator.jquery.css',
	];

	protected static $js = [
		'/assets/backend/libs/Tagator/fm.tagator.jquery.js',
	];

	public function render()
	{
		$name = $this->formatName($this->column);

		$this->script = <<<EOT
	$(function () {
        $('#tags_{$name}').tagator({
            autocomplete: ['标签提示1', '标签提示2', 'third', 'fourth', 'fifth', 'sixth', 'seventh', 'eighth', 'ninth', 'tenth']
        });
    });
EOT;
		return parent::render();
	}
}