@extends('layout.layout')
@section('page')
<ol class="breadcrumb">
    <li><a href="http://<?= ROOT_URL ?>">Dasbboard</a></li>
    <li class="active"><b>Password</b></li>
</ol>
<div class="page">
    <div class="maintain">
        <div  style="padding-left:5%; ">
            <div class='page-header'>
                <h3>Change password</h3>
            </div>
            <div  style='margin-bottom: 20px;'>
      {{$error}}
            </div>
            <form role="form" style="width:40%" method='post' id="formpassword">
                <div class="form-group">
                    <label class="control-label">Current password</label>
                    <input type="password" class="form-control" name="oldpass">
                </div>
                <div class="form-group">
                    <label class="control-label">New password</label>
                    <input type="password" class="form-control" name="newpass">
                </div>
                <div class="form-group">
                    <label class="control-label">Retype New Password</label>
                    <input type="password" class="form-control" name="confirm">
                </div>
                <button type="submit" class="btn btn-primary" name="signin">Save</button>
            </form>
        </div>
    </div>
</div>
        <script type="text/javascript">
            $(document).ready(function(){
                $('#formpassword').bootstrapValidator({
                    message: 'This value is not valid',
                    fields: {
                        oldpass: {
                            validators: {
                                notEmpty: {
                                    message: 'The password is required and can\'t be empty'
                                }
                            }
                        },
                        newpass: {
                            validators: {
                                notEmpty: {
                                    message: 'The password is required and can\'t be empty'
                                },
                                identical: {
                                    field: 'confirm',
                                    message: 'The password and its confirm are not the same'
                                },
                                different: {
                                    field: 'oldpass',
                                    message: 'The password can\'t be the same as oldpass'
                                }
                            }
                        },
                        confirm: {
                            validators: {
                                notEmpty: {
                                    message: 'The password is required and can\'t be empty'
                                },
                                identical: {
                                    field: 'newpass',
                                    message: 'The password and its confirm are not the same'
                                },
                                different: {
                                    field: 'oldpass',
                                    message: 'The password can\'t be the same as oldpass'
                                }
                            }
                        }
                    }
                });
            })
        </script>
@endsection