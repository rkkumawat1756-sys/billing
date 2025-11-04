


@extends('layout.app')
@section('title'){{('Users')}}@endsection
@section('admins')
    <div class="card card-orange-outline">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Teaher List</h4>
            <div class="d-flex gap-2">
                <a class="btn btn-primary" href="{{ route('users.create') }}"><i class="fa fa-plus"></i>Add Users</a>
            </div>
        </div>

        <div class="card-body">
               <table class="table table-bordered table-striped" id="hostelTable">
                                <thead>
                <tr >
                      <th>{{ __('S.NO') }}</th>
                      <th class="text-center">Image</th>
                            <th>{{ __('Teacher Name') }}</th>
                           
                            <th>{{ __('Class Teacher') }}</th>
                           
                            <th>{{ __('Teaching Subject') }}</th>
                           
                          
                            
                            <th>{{ __('Mobile') }}</th>
                            <th>{{ __('E-Mail') }}</th>
                         
                            <!--<th>Joining Date</th>-->
			              <th>{{ __('Action') }}</th>
                       
                          
                    </tr>
            </thead>

              <tbody>
                      
                      @if(!empty($data))
                        @php
                    
                           $i=1;
                        @endphp
                        
                        @foreach ($data  as $key => $item)
                        @php
                            $chat='';
                            $complaint_id= '';
                            $viewStatus= null;
                         $chatData = DB::table('complaint')
                          ->where('session_id', '=',Session::get('session_id'))
                           ->where('branch_id', '=',Session::get('branch_id'))
                         ->where('admission_id', '=',Session::get('id'))->where('teacher_id_to_complaint','=',$item->id ?? '')->whereNull('deleted_at')->first();
                        
                        if(!empty($chatData)){
                              $chat = $chatData->chat ?? '';
                              $complaint_id = $chatData->id;
                              $viewStatus  = json_decode($chatData->view_status,true)[Session::get('id')] ?? 2;
                        }
                        @endphp
                  
                        <tr>
                                <td>{{ $i++ }}</td>
                                <td class="text-center">
                                    <img class="profileImg pointer" src="{{ env('IMAGE_SHOW_PATH').'profile/'.$item['photo'] }}" onerror="this.src='{{ env('IMAGE_SHOW_PATH').'default/user_image.jpg' }}'" data-img="@if(!empty($item->photo)) {{ env('IMAGE_SHOW_PATH').'profile/'.$item['photo'] }} @endif" >
                                </td>
                                
                                @php
                                
                               
                                @endphp
                                <td>{{ $item['first_name']  }} {{ $item['last_name']  }}  <span class='badge badge-primary'>@if(Session::get('role_id') != 1){{$item->class_type_id == Session::get('class_type_id') ? 'Class Teacher' : ''}}@endif</span></td>
                                
                                   @if(Session::get('role_id') != 3  && Session::get('role_id') != 2)
                            <td>
                                
                                @if(isset($item->class_type_id) && !empty($item->class_type_id))
                                    @php
                                        $classIds = unserialize($item->class_type_id);
                                        $classIds = is_array($classIds) ? array_map('intval', $classIds) : [];
                                        
                                        $classes = DB::table('class_types')
                                                    ->whereIn('id', $classIds)
                                                    ->whereNull('deleted_at')
                                                    ->pluck('name', 'id')
                                                    ->toArray();
                                
                                        // Ensure $classNames contains only the names for existing class type IDs
                                        $classNames = array_intersect_key($classes, array_flip($classIds));
                                    @endphp
                                    
                                    {{ implode(', ', $classNames) }}
                                @endif
                        
                      
                            </td>
                            @endif
                            
                            @if(Session::get('role_id') !=  3 && Session::get('role_id') !=  2 ) 
                                <td>
                                    @php
                                    $teachingSubjects = DB::table('teacher_subjects')
                                       ->leftJoin('subject', 'subject.id', '=','teacher_subjects.subject_id')
                                       ->leftJoin('class_types', 'class_types.id', '=','teacher_subjects.class_type_id')
                                    ->where('teacher_subjects.teacher_id',$item->id)->whereNull('teacher_subjects.deleted_at')
                                     ->select('teacher_subjects.*','subject.name as subject_name','class_types.name as class_name')
                                    ->get();
                                  
                                    @endphp
                                 
                                 @if(count($teachingSubjects) > 0)
                                 
                                 @foreach($teachingSubjects as $subjects)
                                 
                                 
                                 {{$subjects->subject_name ?? ''}}({{$subjects->class_name ?? ''}})<br>
                                 
                                 @endforeach
                                 @else
                                    <span class='text-danger' style='font-size:12px'> Timetable not scheduled </span> <br>
                                    <a href="{{url('teacher_subject_add')}}"class='text-primary'  style='font-size:12px;cursor:pointer' target='_blank'>Click here to assign subject</a>
                                 @endif
                                
                                
                                </td>
                                
                               
                                
                                
                                <td>{{ $item['mobile']  }}</td>
                                <td>{{ $item['email']  }}</td>
                                 @endif
                                <!--<td>{{ $item['joining_date']  }}</td>-->
                                  
                         
                                  
                                
                               
                                    
                                     @if(Session::get('role_id') != 3) 
                                      <td>
                                       <a href="" title="View Profile">
                                                <i class="fa fa-user-circle"></i>
                                            </a>


                                       
                                        
                                        
                                                <a href="" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="rgba(0,0,0,1)" viewBox="0 0 24 24">
                                        <path d="M16.7574 2.99678L9.29145 10.4627L9.29886 14.7099L13.537 14.7024L21 7.23943V19.9968C21 20.5491 20.5523 20.9968 20 20.9968H4C3.44772 20.9968 3 20.5491 3 19.9968V3.99678C3 3.4445 3.44772 2.99678 4 2.99678H16.7574ZM20.4853 2.09729L21.8995 3.5115L12.7071 12.7039L11.2954 12.7064L11.2929 11.2897L20.4853 2.09729Z"></path>
                                    </svg>
                                </a>

                                <!-- Delete -->
                                <a href=""
                                   onclick="return confirm('Are you sure you want to delete this bed?');"
                                   title="Delete">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="rgba(243,25,25,1)">
                                        <path d="M4 8H20V21C20 21.5523 19.5523 22 19 22H5C4.44772 22 4 21.5523 4 21V8ZM7 5V3C7 2.44772 7.44772 2 8 2H16C16.5523 2 17 2.44772 17 3V5H22V7H2V5H7ZM9 4V5H15V4H9ZM9 12V18H11V12H9ZM13 12V18H15V12H13Z"></path>
                                    </svg>
                                </a>
                                

                                         </td>
                                        @endif
                               
                           @if(Session::get('role_id') == 3) 
                           
                           
                         
                         
                                <td>
                                    <a class="btn btn-{{$viewStatus == "" ? 'info' : ($viewStatus == 0 ? 'danger' : 'primary')}} btn-xs modal_complaint" id='complaint_id_{{$item->id}}' data-complaint_id="{{$complaint_id}}"data-teacher_name="{{ $item['first_name']  ?? '' }}"data-teacher_id="{{$item->id ?? ''}}" data-chat="{{$chat}}"><i class="fa fa-exclamation-circle" aria-hidden="true" ></i> Start / View Conversation
                                    </a>
                                </td>
                                    
                                    @endif
                          
                              
                      </tr>
                      @endforeach
                @endif
            </tbody>

            </table>
        </div>
    </div>
@endsection