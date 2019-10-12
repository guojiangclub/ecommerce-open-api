<?php

namespace GuoJiangClub\EC\Open\Backend\Store\Extensions;

use Encore\Admin\Grid\Tools\BatchAction;

class BatchAudit extends BatchAction
{
	protected $action;

	public function __construct($action = 1)
	{
		$this->action = $action;
	}

	public function script()
	{
		return <<<EOT

$('{$this->getElementClass()}').on('click', function() {

    swal({
      title: "是否确认操作",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#DD6B55",
      confirmButtonText: "确认",
      closeOnConfirm: false,
      cancelButtonText: "取消"
    },
    function(){
        $.ajax({
	        method: 'post',
	        url: '{$this->resource}/audit',
	        data: {
	            _token:LA.token,
	            ids: selectedRows(),
	            action: {$this->action}
	        },
	        success: function (data) {
	            $.pjax.reload('#pjax-container');
	            if (typeof data === 'object') {
                    if (data.status) {
                        swal(data.message, '', 'success');
                    } else {
                        swal(data.message, '', 'error');
                    }
                }
	        }
        });
    });
});

EOT;

	}
}