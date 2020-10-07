<div class="side-menu">
    <div class="side-menu-body">
        <ul>
            <li class="side-menu-divider">فهرست</li>

            <li><a @if (Illuminate\Support\Facades\Route::currentRouteName()=='Dashboard' ) class="active" @endif
                    href="{{route('Dashboard')}}"><i class="icon ti-home"></i> <span>داشبورد</span> </a></li>

            <li><a href=""><i class="icon ti-user"></i> <span> کاربران </span></a>
                <ul>
                    <li><a @if (Illuminate\Support\Facades\Route::currentRouteName()=='Pannel.User.List' )
                            class="active" @endif href="{{route('Pannel.User.List')}}"> <span> مدیریت کاربران </span>
                        </a> </li>

                   
                  
                    
                </ul>
            </li>


            <li><a href="#"><i class="icon ti-shopping-cart"></i> <span> محصولات</span> </a>
                <ul>
                    <li><a @if (Illuminate\Support\Facades\Route::currentRouteName()=='Pannel.Services.Stores' )
                            class="active" @endif href=" {{route('Pannel.Services.Stores')}} "><i
                                class="icon ti-list"></i> <span> لیست </span> </a></li>
                    <li><a @if (Illuminate\Support\Facades\Route::currentRouteName()=='GoodsOrders' ) class="active"
                            @endif href=" {{route('GoodsOrders')}} "><i class="icon ti-list"></i> <span> سفارشات کالا
                            </span> </a></li>
                </ul>
            </li>
            
            <li><a href="#"><i class="icon ti-shopping-cart"></i> <span> دسته بندی </span> </a>
                <ul>
                    <li><a @if (Illuminate\Support\Facades\Route::currentRouteName()=='Pannel.Category' )
                            class="active" @endif href=" {{route('Pannel.Category')}} "><i
                                class="icon ti-list"></i> <span> لیست </span> </a></li>
                 
                </ul>
            </li>

            <li><a @if (Illuminate\Support\Facades\Route::currentRouteName()=='Pannel.Setting' ) class="active" @endif
                    href=" {{route('Pannel.Setting')}}"><i class="icon ti-layout"></i> <span> تنظیمات </span> </a></li>

        </ul>
    </div>
</div>