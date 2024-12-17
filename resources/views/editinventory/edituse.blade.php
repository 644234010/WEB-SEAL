<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if (session('status'))
                        <div class="mb-4 font-medium text-sm text-green-600">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form id="editinventoryForm" method="POST" action="{{ route('editinventory.update', ['id' => $user->id]) }}">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="name">ชื่อ</label>
                            <input type="text" name="name" id="name" class="form-control"
                                value="{{ $user->name }}" required autofocus />
                        </div>
                        @error('name')
                            <div class="my-2">
                                <span class="text text-danger">{{ $message }}</span>
                            </div>
                        @enderror

                        <div class="form-group mt-4">
                            <label for="email">อีเมล์</label>
                            <input type="email" name="email" id="email" class="form-control"
                                value="{{ $user->email }}" required />
                        </div>
                        @error('email')
                            <div class="my-2">
                                <span class="text text-danger">{{ $message }}</span>
                            </div>
                        @enderror

                        <div class="form-group">
                            <label for="address">ที่อยู่</label>
                            <input type="text" name="address" id="address" class="form-control"
                                value="{{ $user->address }}" required autofocus />
                        </div>
                        <div class="form-group">
                            <label for="address1">ที่อยู่1</label>
                            <input type="text" name="address1" id="address1" class="form-control"
                                value="{{ $user->address1 }}" required autofocus />
                        </div>
                        <div class="form-group">
                            <label for="address2">ที่อยู่2</label>
                            <input type="text" name="address2" id="address2" class="form-control"
                                value="{{ $user->address2 }}" required autofocus />
                        </div>
                        <div class="form-group">
                            <label for="address3">ที่อยู่3</label>
                            <input type="text" name="address3" id="address3" class="form-control"
                                value="{{ $user->address3 }}" required autofocus />
                        </div>
                        @error('address')
                            <div class="my-2">
                                <span class="text text-danger">{{ $message }}</span>
                            </div>
                        @enderror

                        <div class="form-group">
                            <label for="phone_number">เบอร์มือถือ</label>
                            <input type="text" name="phone_number" id="phone_number" class="form-control"
                                value="{{ $user->phone_number }}" required autofocus maxlength="10"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);" />
                        </div>

                        @error('phone_number')
                            <div class="my-2">
                                <span class="text text-danger">{{ $message }}</span>
                            </div>
                        @enderror

                        <input type="submit" id="saveButton" value="บันทึก" class="btn btn-primary my-3">
                        <button id="homeButton" class="btn btn-success">กลับหน้าจักการข้อมูลสมาชิก</button>
                    </form>


                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#saveButton').on('click', function(event) {
                event.preventDefault();

                Swal.fire({
                    title: "คุณต้องการแก้ไขข้อมูลสมาชิก หรือไม่?",
                    showDenyButton: true,
                    showCancelButton: true,
                    confirmButtonText: "บันทึก",
                    cancelButtonText: 'ยกเลิก',
                    denyButtonText: `ไม่บันทึก`
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#editinventoryForm').off('submit').submit();
                    } else if (result.isDenied) {
                        Swal.fire("คุณไม่ได้บันทึกข้อมูลสมาชิก", "", "info");
                    }
                });
            });

            $('#homeButton').on('click', function(event) {
                event.preventDefault();

                Swal.fire({
                    title: "คุณแน่ใจหรือไม่?",
                    text: "คุณจะกลับหน้าจัดการข้อมูลสมาชิก หรือไม่?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    cancelButtonText: 'ยกเลิก',
                    confirmButtonText: "ใช่"
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: "หน้าจัดการข้อมูลสมาชิก!",
                            text: "กลับสู่หน้าจัดการข้อมูลสมาชิก",
                            icon: "success"
                        }).then(() => {
                            window.location.href = "{{ route('editinventory.userall') }}";
                        });
                    }
                });
            });
        });
    </script>
</x-app-layout>
