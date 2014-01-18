@extends('layout.template')
@section('content')
<div class="col-md-4"></div>
<div class="col-md-4" style="padding: 0 5%;">
    <h3 class="text-center" style="padding-top:40px;font-weight: bold;"> <a href="http://<?= ROOT_URL ?>" style="text-decoration: none">Feedback Solution</a></h3>
    {{$error}}
    <div class="panel panel-default">
        <div class="panel-heading">
            Sign-in
        </div>

        <form method='post' class="panel-body" id="form">
            <div class="form-group">
                <input type="text" class="form-control" id="userName" placeholder="Username" name="user_name" style="height:40px;" >
            </div>
            <div class="form-group">
                <input type="password" class="form-control" id="password" name="password" placeholder="Password" style="height:40px;" >
            </div>
<!--            <div class="checkbox">-->
<!--                <label>-->
<!--                    <div>-->
<!--                        <input type="checkbox" name="rememberme" class="checkbox" style="min-height: 0;">Remember me-->
<!--                    </div>-->
<!--                </label>-->
<!--            </div>-->
            <div class="form-group">
                  <button type="submit" class="btn btn-primary" name="signin">Sign in</button>
            </div>
           <div class="form-group"><a href='' data-toggle="modal" data-target="#myModal">Forgot Password?</a> | <a href="http://<?= ROOT_URL ?>/register">Register here</a></div>
        </form>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Recover password</h4>
                </div>
                <form method='post' action='/password/forgot' id='recover'>
                    <div class="modal-body">
                            <div class="form-group">
                                <label class="control-label">Email address</label>
                                <input type="text" class="form-control recoverInput" name="email" style="height:40px;width:400px;" >
                            </div>
                            <div class="form-group">
                                <label id="captchaOperation" class="control-label"></label>
                                <input type="text" class="form-control" name="captcha" style="height:40px;width:400px;">
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="signin" class="btn btn-primary recoverBtn">Confirm</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

</div>
<script type="text/javascript">
    $(document).ready(function() {
        $('#form').bootstrapValidator({
            message: 'This value is not valid',
            fields: {
                user_name: {
                    message: 'The username is not valid',
                    validators: {
                        notEmpty: {
                            message: 'The username is required and can\'t be empty'
                        },
                        stringLength: {
                            min: 3,
                            max: 15,
                            message: 'The username must be more than 3 and less than 15 characters long'
                        },
                        regexp: {
                            regexp: /^[a-zA-Z0-9_\.]+$/,
                            message: 'The username can only consist of alphabetical, number, dot and underscore'
                        }
                    }
                },
                password: {
                    validators: {
                        notEmpty: {
                            message: 'The password is required and can\'t be empty'
                        }
                    }
                }
            }
        });

        // Generate a simple captcha
        function randomNumber(min, max) {
            return Math.floor(Math.random() * (max - min + 1) + min);
        };
        $('#captchaOperation').html([randomNumber(1, 100), '+', randomNumber(1, 200), '='].join(' '));

        $('#recover').bootstrapValidator({
            message: 'This value is not valid',
            fields: {
                email: {
                    validators: {
                        notEmpty: {
                            message: 'The email address is required and can\'t be empty'
                        },
                        emailAddress: {
                            message: 'The input is not a valid email address'
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
    });
</script>
@endsection