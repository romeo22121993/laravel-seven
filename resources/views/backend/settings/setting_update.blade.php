@extends('admin.admin_master')
    @section('admin')

    <div class="container-full">

        <section class="content">

            <!-- Basic Forms -->
            <div class="box">
                <form method="post" action="{{ route('update.sitesetting') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="box-header with-border">
                        <h4 class="box-title">Site Setting Page </h4>
                    </div>

                    <div class="box-body">
                        <div class="row">
                            <div class="col">
                                <input type="hidden" name="id" value="{{ $settings->id }}">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-md-6">

                                                <div class="form-group">
                                                    <h5>Site Logo  <span class="text-danger"> </span></h5>
                                                    <div class="controls">
                                                        <input type="file" name="logo" class="form-control product_thambnail" >
{{--                                                        <img id="mainThmb" src="{{ asset( $settings->logo) }}" alt="logo" class="product_thambnail">--}}
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <h5>Phone One <span class="text-danger">*</span></h5>
                                                    <div class="controls">
                                                        <input type="text" name="phone_one" class="form-control" value="{{ $settings->phone_one }}" >
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <h5>Phone Two <span class="text-danger">*</span></h5>
                                                    <div class="controls">
                                                        <input type="text" name="phone_two" class="form-control"  value="{{ $settings->phone_two }}"  >
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <h5>Email <span class="text-danger">*</span></h5>
                                                    <div class="controls">
                                                        <input type="email" name="email" class="form-control" value="{{ $settings->email }}"   >
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <h5>Company Name <span class="text-danger">*</span></h5>
                                                    <div class="controls">
                                                        <input type="text" name="company_name" class="form-control" value="{{ $settings->company_name }}"   >
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <h5>Company Address <span class="text-danger">*</span></h5>
                                                    <div class="controls">
                                                        <input type="text" name="company_address" class="form-control" value="{{ $settings->company_address }}"   >
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <h5>Facebook <span class="text-danger">*</span></h5>
                                                    <div class="controls">
                                                        <input type="text" name="facebook" class="form-control" value="{{ $settings->facebook }}"   >
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <h5>Twitter <span class="text-danger">*</span></h5>
                                                    <div class="controls">
                                                        <input type="text" name="twitter" class="form-control"  value="{{ $settings->twitter }}"  >
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <h5>Linkedin <span class="text-danger">*</span></h5>
                                                    <div class="controls">
                                                        <input type="text" name="linkedin" class="form-control"  value="{{ $settings->linkedin }}"  >
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <h5>Youtube <span class="text-danger">*</span></h5>
                                                    <div class="controls">
                                                        <input type="text" name="youtube" class="form-control"  value="{{ $settings->youtube }}"  >
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="box-header with-border">
                        <h4 class="box-title">Seo Setting Page </h4>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col">

                                <div class="row">
                                    <div class="col-12">

                                        <div class="row">
                                            <div class="col-md-6">

                                                <div class="form-group">
                                                    <h5>Meta Title <span class="text-danger">*</span></h5>
                                                    <div class="controls">
                                                        <input type="text" name="meta_title" class="form-control" value="{{ $settings->meta_title }}" >
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <h5>Meta Author <span class="text-danger">*</span></h5>
                                                    <div class="controls">
                                                        <input type="text" name="meta_author" class="form-control"  value="{{ $settings->meta_author }}"  >
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <h5>Meta Keyword <span class="text-danger">*</span></h5>
                                                    <div class="controls">
                                                        <input type="text" name="meta_keyword" class="form-control" value="{{ $settings->meta_keyword }}"   >
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <h5>Meta Description <span class="text-danger">*</span></h5>
                                                    <div class="controls">
                                                        <textarea name="meta_description" id="textarea" class="form-control" required placeholder="Textarea text">{{ $settings->meta_description }}</textarea>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <h5>Google Analytics <span class="text-danger">*</span></h5>
                                                    <div class="controls">
                                                        <textarea name="google_analytics" id="textarea" class="form-control" required placeholder="Textarea text">{{ $settings->google_analytics }}</textarea>
                                                    </div>
                                                </div>

                                            </div>

                                        </div>

                                        <div class="text-xs-right">
                                            <input type="submit" class="btn btn-rounded btn-primary mb-5" value="Update">
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

        </section>

    </div>

@endsection
