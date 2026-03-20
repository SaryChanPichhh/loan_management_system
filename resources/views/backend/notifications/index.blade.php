@extends('backend.layout.master')

@section('contents')
    <div class="page-wrapper mt-1 mb-4">
        
        <!-- Header Section -->
        <div class="d-flex align-items-center justify-content-between ml-4 mr-4 mb-4">
            <h4 class="mb-0 font-weight-bold text-dark">ការជូនដំណឹង (Notifications)</h4>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-secondary rounded-pill shadow-sm px-4 d-flex align-items-center transition-hover">
                    <i data-feather="check-square" class="mr-2" style="width: 18px; height: 18px;"></i> ធីកថាបានអានទាំងអស់
                </button>
            </div>
        </div>

        <div class="ml-4 mr-4">
            <div class="card shadow-sm border-0 rounded-xl overflow-hidden">
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        
                        @forelse($notifications as $notification)
                            <a href="#" class="list-group-item list-group-item-action p-4 border-bottom {{ $notification->is_read ? 'bg-white' : 'bg-light' }}" style="border-left: {{ $notification->is_read ? '4px solid transparent' : '4px solid #007bff' }}; transition: all 0.2s ease-in-out;">
                                <div class="d-flex align-items-start">
                                    
                                    <!-- Dynamic Animated Icon based on Notification Type -->
                                    <div class="mr-4 mt-1 shadow-sm rounded-circle d-flex align-items-center justify-content-center 
                                        @if($notification->type == 'success') bg-success text-white
                                        @elseif($notification->type == 'error') bg-danger text-white
                                        @elseif($notification->type == 'warning') bg-warning text-dark
                                        @else bg-primary text-white @endif" 
                                        style="width: 48px; height: 48px; min-width: 48px;">
                                        
                                        @if($notification->type == 'success')
                                            <i data-feather="check" style="width: 22px; height: 22px;"></i>
                                        @elseif($notification->type == 'error')
                                            <i data-feather="x" style="width: 22px; height: 22px;"></i>
                                        @elseif($notification->type == 'warning')
                                            <i data-feather="alert-triangle" style="width: 22px; height: 22px;"></i>
                                        @else
                                            <i data-feather="info" style="width: 22px; height: 22px;"></i>
                                        @endif
                                    </div>
                                    
                                    <!-- Main Content Array -->
                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-center justify-content-between mb-1">
                                            <h6 class="mb-0 {{ $notification->is_read ? 'text-secondary' : 'font-weight-bold text-dark' }} text-truncate" style="max-width: 65%;">
                                                {{ $notification->title }}
                                            </h6>
                                            <small class="text-muted d-flex align-items-center font-weight-medium">
                                                <i data-feather="clock" class="mr-1" style="width: 13px; height: 13px;"></i> 
                                                {{ $notification->created_at->diffForHumans() }}
                                            </small>
                                        </div>
                                        
                                        <!-- Message -->
                                        <p class="mb-3 mt-2 {{ $notification->is_read ? 'text-muted' : 'text-dark' }}" style="font-size: 0.95rem; line-height: 1.5;">
                                            {{ $notification->message }}
                                        </p>
                                        
                                        <!-- Footer/Metadata for the notification -->
                                        <div class="d-flex align-items-center gap-2">
                                            @if($notification->target_user)
                                                <span class="badge badge-light text-secondary border rounded-pill px-3 py-1 font-weight-medium">
                                                    <i data-feather="user" style="width:12px; height:12px;" class="mr-1"></i> ផ្ញើទៅកាន់: {{ $notification->target_user }}
                                                </span>
                                            @else
                                                <span class="badge badge-light text-secondary border rounded-pill px-3 py-1 font-weight-medium">
                                                    <i data-feather="users" style="width:12px; height:12px;" class="mr-1"></i> ទូទៅ (All Users)
                                                </span>
                                            @endif
                                            
                                            <small class="text-muted ml-3 ml-md-0 ml-lg-3"><i data-feather="calendar" style="width:12px; height:12px;" class="mr-1"></i> {{ $notification->created_at->format('d M, Y \a\t h:i A') }}</small>
                                        </div>
                                    </div>
                                    
                                    <!-- Unread Dot Indicator -->
                                    <div class="ml-3 d-flex align-items-center h-100 mt-2">
                                        @if(!$notification->is_read)
                                            <div class="bg-primary rounded-circle shadow-sm" style="width: 12px; height: 12px;" title="មិនទាន់អាន (Unread)"></div>
                                        @endif
                                    </div>
                                    
                                </div>
                            </a>
                        @empty
                            <!-- Empty State Design -->
                            <div class="text-center py-5 my-4">
                                <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                    <i data-feather="bell-off" class="text-muted opacity-50" style="width: 40px; height: 40px;"></i>
                                </div>
                                <h5 class="text-dark font-weight-bold">មិនមានការជូនដំណឹងទេ</h5>
                                <p class="text-muted">You're all caught up! No notifications to display.</p>
                            </div>
                        @endforelse
                        
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-4 pt-2 d-flex justify-content-end">
                {{ $notifications->links('pagination::bootstrap-5') }}
            </div>
            
        </div>
    </div>

    @push('styles')
    <style>
        .rounded-xl {
            border-radius: 1rem !important;
        }
        .transition-hover:hover {
            background-color: #f8f9fa;
            transform: translateY(-1px);
        }
        .list-group-item:hover {
            background-color: #fdfdfd !important;
        }
    </style>
    @endpush
    
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        });
    </script>
    @endpush
@endsection
