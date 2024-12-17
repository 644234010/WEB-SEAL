<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white text-center">
                    <h2>รายชื่อพนักงานฝ่ายคลัง</h2>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">ชื่อ</th>
                                <th scope="col">อีเมล</th>
                                <th scope="col">ที่อยู่</th>
                                <th scope="col">เบอร์มือถือ</th>
                                <th scope="col">การดำเนินการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                @if ($user->type == 4)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->address }}</td>
                                        <td>{{ $user->phone_number }}</td>
                                        <td>
                                            <a href="{{ route('editinventory.edit', ['id' => $user->id]) }}"
                                                class="btn btn-sm btn-warning edit-btn"
                                                data-name="{{ $user->name }}">แก้ไข</a>
                                            <form action="{{ route('editinventory.destroy', ['id' => $user->id]) }}"
                                                method="POST" class="d-inline delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger delete-btn"
                                                    data-name="{{ $user->name }}">ลบ</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            const userName = button.getAttribute('data-name');
            Swal.fire({
                title: 'แก้ไขผู้ใช้',
                text: `คุณแน่ใจหรือไม่ว่าต้องการแก้ไขข้อมูลของ ${userName}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ใช่, แก้ไข!',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = button.getAttribute('href');
                }
            });
        });
    });

    document.querySelectorAll('.delete-form').forEach(form => {
        form.querySelector('.delete-btn').addEventListener('click', function(event) {
            event.preventDefault();
            const userName = form.querySelector('.delete-btn').getAttribute('data-name');
            Swal.fire({
                title: 'ลบผู้ใช้',
                text: `คุณแน่ใจหรือไม่ว่าต้องการลบ ${userName}? การกระทำนี้ไม่สามารถยกเลิกได้`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ใช่, ลบ!',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
