var myApp = angular.module("myApp",[]);
myApp.controller("FirstCtrl",function($scope){
    $scope.uploads = function(){
        console.log("ok");
    }
});