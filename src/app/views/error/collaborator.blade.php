@extends('layout.template')
@section('content')
<div class="alert alert-danger">Sorry, You don't have privilege to view page </div>
<script>
    $(document).ready(function(){
        $('body').css('padding-top','0px');
    })
</script>
@endsection