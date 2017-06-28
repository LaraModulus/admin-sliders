@extends('admincore::layouts.dashboard')

@section('content')
    <div id="page-wrapper" data-ng-app="App" data-ng-controller="sliderController">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Add/Edit Slider</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        @if(count($errors))
            <div class="row">
                <div class="col-xs-12">
                    <div class="alert alert-danger">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{$error}}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
    @endif
    <!-- /.row -->
        <form action="{{route('admin.sliders.form', ['id' => $item->id])}}" method="post" role="form">
            <div class="row">
                <div class="col-md-9">
                    <!-- TAB NAVIGATION -->
                    <ul class="nav nav-tabs" role="tablist">
                        @foreach(config('app.locales', ['en']) as $key => $locale)
                            <li @if($key==0) class="active" @endif><a href="#{{$locale}}" role="tab"
                                                                      data-toggle="tab">{{$locale}}</a></li>
                        @endforeach
                    </ul>
                    <!-- TAB CONTENT -->
                    <div class="tab-content">
                        @foreach(config('app.locales', ['en']) as $key => $locale)
                            <div class="@if($key==0) active fade in @endif tab-pane panel panel-default"
                                 id="{{$locale}}">

                                <div class="panel-body">
                                    <div class="form-group">
                                        <label for="title_{{$locale}}" class="control-label">Title</label>

                                        <input type="text" class="form-control" name="title_{{$locale}}"
                                               id="title_{{$locale}}"
                                               placeholder=""
                                               value="{{old('title_'.$locale, $item->{'title_'.$locale})}}">

                                    </div>
                                    <div class="form-group">
                                        <label for="sub_title_{{$locale}}" class="control-label">Sub title</label>

                                        <input type="text" class="form-control" name="sub_title_{{$locale}}"
                                               id="sub_title_{{$locale}}"
                                               placeholder=""
                                               value="{{old('sub_title_'.$locale, $item->{'sub_title_'.$locale})}}">

                                    </div>
                                    <div class="form-group">
                                        <label for="description_{{$locale}}"
                                               class="control-label">Description </label>

                                        <textarea name="description_{{$locale}}" id="description_{{$locale}}" cols="30"
                                                  rows="10"
                                                  class="form-control editor">{{old('description_'.$locale, $item->{'description_'.$locale})}}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="link_{{$locale}}" class="control-label">Link</label>

                                        <input type="text" class="form-control" name="link_{{$locale}}"
                                               id="link_{{$locale}}"
                                               placeholder=""
                                               value="{{old('link_'.$locale, $item->{'link_'.$locale})}}">

                                    </div>
                                    <div class="form-group">
                                        <label for="image_{{$locale}}" class="control-label">Image</label>
                                        <div>
                                            @if($item->{'image_'.$locale})
                                                <img id="file_image_{{$locale}}" src="{{\LaraMod\Admin\Files\Models\Files::find($item->{'image_'.$locale})->thumb}}">
                                            @endif
                                        </div>
                                        <div>
                                            <input type="hidden" class="form-control" name="image_{{$locale}}"
                                                   id="image_{{$locale}}"
                                                   placeholder=""
                                                   value="{{old('image_'.$locale, $item->{'image_'.$locale})}}">
                                            <button type="button" class="btn btn-primary"
                                                    data-ng-click="selectSingleFile('image_{{$locale}}')">Select file
                                            </button>
                                        </div>
                                    </div>

                                </div>

                            </div>
                        @endforeach
                    </div>


                </div>
                <div class="col-md-3">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="form-group">
                                <label for="viewable">Visible?</label>
                                <div class="checkbox">
                                    <input type="checkbox" value="1" id="viewable" name="viewable"
                                           @if($item->viewable || !$item->id) checked @endif>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="from_date">From date</label>
                                <input type="text" name="from_date" id="from_date" class="form-control datetimepicker"
                                       value="{{old('from_date', $item->from_date)}}">
                            </div>
                            <div class="form-group">
                                <label for="to_date">To date</label>
                                <input type="text" name="to_date" id="to_date" class="form-control datetimepicker"
                                       value="{{old('to_date', $item->to_date)}}">
                            </div>
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-primary btn-lg btn-block">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <div class="panel panel-default" data-ng-controller="filesContainerController">

            <div class="modal fade" id="filesModal">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                &times;
                            </button>
                            <h4 class="modal-title">Select file</h4>
                        </div>
                        <div class="modal-body">
                            @include('adminfiles::_partials.manager')
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close
                            </button>
                            <button type="button" class="btn btn-primary"
                                    data-ng-click="addSingleFile(field_id)">Select file
                            </button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
        </div>
    </div>
    @if(class_exists(\LaraMod\Admin\Files\AdminFilesServiceProvider::class))
        <script>
            app.controller('sliderController', function ($scope, $http, SweetAlert, CSRF_TOKEN, $window, Files) {
                $scope.files = Files;
                $scope.files_loading = false;

                $scope.$watch($scope.files, function (newVal, oldVal) {
                    Files = $scope.files;
                }, true);

                $scope.selectSingleFile = function(field_id){
                    $scope.field_id = field_id;
                    $('#filesModal').modal('show');
                }
                $scope.addSingleFile = function(input_id) {
                    $('#'+input_id).val($scope.files.selected_ids[0]);
                    $('img#file_'+input_id).attr('src', $scope.files.selected[0].thumb.encoded);
                    $scope.files.selected_ids = [];
                    $scope.files.selected = [];
                    $('#filesModal').modal('hide');
                }

            });
        </script>
    @endif
@stop