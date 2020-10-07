<nav class="navbar">
    <div class="container-fluid">
        <div class="header-logo">
            <a href="#">
                <img src="{{route('BaseUrl')}}/Pannel/img/c.jpg" alt="...">
                <span class="logo-text d-none d-lg-block">افق آریا </span>
            </a>
        </div>
        <div>
            
        <h5 style="margin: 0;
        padding-right: 20px;"> آریا عین آبادی</h5>
        </div>

        <div class="header-body">
            <ul class="navbar-nav">
                {{-- <li class="nav-item">
                    <a href="#" class="d-lg-none d-sm-block nav-link search-panel-open">
                        <i class="fa fa-search"></i>
                    </a>
                </li> --}}
                <li class="nav-item datetime" >
                    <a  href="#" class="nav-link" >
                        تاریخ

                        <span class="date">
                            
                            {{\Morilog\Jalali\Jalalian::forge('today')->format('%A, %d %B %y')}}
                        </span>

                        ساعت
                        <span class="date" id='server_time' style="width:75px;">

                        </span>
                    </a>
                </li>
                <li class="nav-item" id="notificontainer">
                      
                    <div class="dropdown-menu dropdown-menu-right" id="notiifcationdialog">
                        <a href="profile.html" class="dropdown-item"
                        data-toggle="modal" data-target=".modal-profile1"
                        >
                        <i class="fa fa-envelope ml-2"></i>

                        پروفایل</a>
                        <div class="dropdown-divider"></div>
                       
                      </div>
                </li>
                
                <li class="nav-item dropdown">
                    <a href="#" data-toggle="dropdown" aria-expanded="false">
                        <figure class="avatar avatar-sm avatar-state-success">
                            <img class="rounded-circle" src="{{route('BaseUrl')}}/Pannel/img/profile.png" alt="...">
                        </figure>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="profile.html" class="dropdown-item"
                        data-toggle="modal" data-target=".modal-profile"
                        >پروفایل</a>
                        <div class="dropdown-divider"></div>
                        <a href="{{route('User.Logout')}}" class="text-danger dropdown-item">خروج</a>
                    </div>
                </li>
             
                <li class="nav-item d-lg-none d-sm-block">
                    <a href="#" class="nav-link side-menu-open">
                        <i class="ti-menu"></i>
                    </a>
                </li>
            </ul>
        </div>

    </div>
</nav>

<div class="modal fade modal-profile" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content content-profile">

    </div>
  </div>
</div>

<div class="modal fade modal-profile1" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">نمایش اطلاعیه</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
      
            
      
      
              <div class="modal-body" id="shownotif">
                  <div class="row">
                      <div class="form-group col-md-12">
                        <label for="recipient-name" class="col-form-label">
                            تیتر :
                        </label>

                        

                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group col-md-12">
                        <label for="user_address" class="col-form-label"> متن اطلاعیه:
                           
                            
                        
                        
                        </label>
                        </textarea>
                      </div>
                    </div>
      
                    <div class="row">
                      <div class="form-group col-md-12">
                        <label for="link" class="col-form-label"> تاریخ:

                    
                        </label>
                      </div>
                    </div>
      
    
                
      
          </div>
      
                
            <!-- form-group -->
      
              
              
              
      
            
    </div>
  </div>
</div>