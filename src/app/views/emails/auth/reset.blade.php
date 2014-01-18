@extends('layout.template')
@section('content')
<div class="col-md-4"></div>
<div class="col-md-4" style="padding: 0 5%;">
    <h3 class="text-center" style="padding-top:40px;font-weight: bold;"> <a href="http://<?= ROOT_URL ?>" style="text-decoration: none">Feedback Solution</a></h3>
    <div class="panel panel-default">
        <div class="panel-heading">
            Reset Password
        </div>
        <form method='post' id="resetPassword" class="panel-body" >
            <div class="form-group">
                <label class="control-label">New password</label>
                <input type="password" class="form-control" name="newpass" style="height:40px;">
            </div>
            <div class="form-group">
                <label class="control-label">Retype New Password</label>
                <input type="password" class="form-control" name="confirm" style="height:40px;">
            </div>
            <div class="form-group">
                <label id="captchaOperation" class="control-label"></label>
                <input type="text" class="form-control" name="captcha" style="height:40px;">
            </div>
            <button type="submit" class="btn btn-primary" name="signin">Save</button>
        </form>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        function randomNumber(min, max) {
            return Math.floor(Math.random() * (max - min + 1) + min);
        };
        $('#captchaOperation').html([randomNumber(1, 100), '+', randomNumber(1, 200), '='].join(' '));
        $('#resetPassword').bootstrapValidator({
            message: 'This value is not valid',
            fields: {
                newpass: {
                    validators: {
                        notEmpty: {
                            message: 'The password is required and can\'t be empty'
                        },
                        stringLength: {
                            min: 3,
                            message: 'The password must be more than 3 long'
                        },
                        identical: {
                            field: 'confirm',
                            message: 'The password and its confirm are not the same'
                        }
                    }
                },
                confirm: {
                    validators: {
                        notEmpty: {
                            message: 'The password is required and can\'t be empty'
                        },
                        stringLength: {
                            min: 3,
                            message: 'The password must be more than 3 long'
                        },
                        identical: {
                            field: 'newpass',
                            message: 'The password and its confirm are not the same'
                        }
                    }
                },
                captcha: {
                    validators: {
                        callback: {
                            message: 'Wrong answer',
                            callback: function(value, validator) {
                                var items = $('#captchaOperation').html().split(' '), sum = parseInt(items[0]) + parseInt(items[2]);
                                return value == sum;
                            }
                        }
                    }
                }
            }
        });
    })
</script>
@endsection