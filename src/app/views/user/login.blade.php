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
            <div class="form-group">If you don't have account ,then <a href="http://<?= ROOT_URL ?>/register">register here</a></div>
            <div class="form-group">
                <input type="text" class="form-control" id="userName" placeholder="Username" name="user_name" style="height:40px;" >
            </div>
            <div class="form-group">
                <input type="password" class="form-control" id="password" name="password" placeholder="Password" style="height:40px;" >
            </div>
            <div class="checkbox">
                <label>
                    <div>
                        <input type="checkbox" name="rememberme" class="checkbox" style="min-height: 0;">Remember me
                    </div>
                </label>
            </div>
            <button type="submit" class="btn btn-primary" name="signin">Sign in</button>
        </form>
    </div>
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
                            max: 10,
                            message: 'The username must be more than 3 and less than 10 characters long'
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
    });
</script>
@endsection