
        <div class="sidebar-wrapper" data-layout="stroke-svg">
          <div>
            <div class="logo-wrapper"><a href="/">
             <img class="for-light" src="{{ asset(getSettingValue('logo', 'assets/images/logdo.png')) }}" alt="Logo">
</a>
              <div class="back-btn"><i class="fa fa-angle-left"></i></div>
              <div class="toggle-sidebar">
                <svg class="stroke-icon sidebar-toggle status_toggle middle">
                  <use href="{{ asset('assets/images/icon-sprite.svg#toggle-icon')}}"></use>
                </svg>
                <svg class="fill-icon sidebar-toggle status_toggle middle">
                  <use href="{{ asset('assets/images/icon-sprite.svg#fill-toggle-icon')}}"></use>
                </svg>
              </div>
            </div>
            <div class="logo-icon-wrapper"><a href="/"><img class="img-fluid" src="{{ asset(getSettingValue('logo', 'assets/images/logdo.png')) }}" style="width: 30px;" alt=""></a></div>
            <nav class="sidebar-main">
              <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
              <div id="sidebar-menu">
                @php
                    $permissions = auth()->user()->role->permissions;
                    $sidebars = getSidebarData(0, $permissions);
                @endphp
                
                <ul class="sidebar-links" id="simple-bar" style="padding-bottom: 20px;">
                  <li class="back-btn"><a href="/"><img class="img-fluid" src="{{ asset('assets/images/logo-icon.png') }}" alt=""></a>
                    <div class="mobile-back text-end"><span>Back</span><i class="fa fa-angle-right ps-2" aria-hidden="true"></i></div>
                  </li>
                  @foreach($sidebars as $sidebar)
                      <li class="sidebar-list">
                          
                          <a class="sidebar-link {{ count($sidebar['children']) ? 'sidebar-title' : '' }}" href="{{ $sidebar['url'] ? url($sidebar['url']) : 'javascript:void(0)' }}">
                            @if($sidebar['icons'])
                              <i class="fa {{ $sidebar['icons'] }}"></i>
                            @endif
                          <span>{{ $sidebar['name'] }}</span>
                              @if(count($sidebar['children']))
                                  <span class="sub-arrow"><i class="fa fa-angle-right"></i></span>
                              @endif
                          </a>

                          @if(count($sidebar['children']))
                              <ul class="sidebar-submenu">
                                  @foreach($sidebar['children'] as $child)
                                      <li>
                                          <a class="{{ count($child['children']) ? 'submenu-title' : '' }}" href="{{ $child['url'] ? url($child['url']) : 'javascript:void(0)' }}">
                                              {{ $child['name'] }}
                                              @if(count($child['children']))
                                                  <span class="sub-arrow"><i class="fa fa-angle-right"></i></span>
                                              @endif
                                          </a>

                                          @if(count($child['children']))
                                              <ul class="nav-sub-childmenu submenu-content">
                                                  @foreach($child['children'] as $subchild)
                                                      <li><a href="{{ $subchild['url'] ? url($subchild['url']) : 'javascript:void(0)' }}">{{ $subchild['name'] }}</a></li>
                                                  @endforeach
                                              </ul>
                                          @endif
                                      </li>
                                  @endforeach
                              </ul>
                          @endif
                      </li>
                  @endforeach
                </ul>
              </div>
              <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
              
            </nav>
          </div>
        </div>