<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('User List') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="container mx-auto">
            <div class="card shadow-sm">
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <!-- Search Form -->
                    <form class="search-form d-flex mb-4" role="search">
                        <input class="form-control me-2" type="search" name="query"
                            placeholder="ค้นหาสมาชิกด้วย ชื่อ หรือ อีเมล์" aria-label="Search" id="search-input">
                    </form>

                    <!-- User List -->
                    <div id="users-list">
                        @include('editfincereport.user_list', ['users' => $users])
                    </div>
                    <div id="pagination-links" class="mt-4">
                        @include('editfincereport.pagination', ['users' => $users])
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            function fetch_data(query = '', page = 1) {
                $.ajax({
                    url: "{{ route('editfincereport.search') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        query: query,
                        page: page
                    },
                    success: function(data) {
                        $('#users-list').html(data.users);
                        $('#pagination-links').html(data.pagination);
                    }
                });
            }

            $('#search-input').on('keyup', function() {
                var query = $(this).val();
                fetch_data(query);
            });

            $(document).on('click', '.pagination a', function(event) {
                event.preventDefault();
                var page = $(this).data('page');
                var query = $('#search-input').val();
                fetch_data(query, page);
            });
        });
    </script>

    <style>
        .search-form .form-control {
            width: 500px;
        }
    </style>
</x-app-layout>
