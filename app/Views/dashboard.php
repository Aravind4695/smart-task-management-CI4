<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="container mt-5">

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Welcome, <?= session()->get('user')['name'] ?></h3>

    <button class="btn btn-danger" onclick="logout()">Logout</button>
</div>

<h2>Task List</h2>

<button class="btn btn-success mb-3" onclick="openAddModal()">+ Add Task</button>

<input type="text" id="search" class="form-control mb-2" placeholder="Search task...">

<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody id="taskTable"></tbody>
</table>
<div id="pagination" class="mt-3"></div>

<!-- Add Task Modal -->
<div class="modal fade" id="addModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Add Task</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <input type="text" id="add_title" class="form-control mb-2" placeholder="Task Title" required>
        <textarea id="add_desc" class="form-control mb-2" placeholder="Description"></textarea>

        <select id="add_status" class="form-control">
            <option value="pending">Pending</option>
            <option value="completed">Completed</option>
        </select>
      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-success" onclick="addTask()">Add</button>
      </div>

    </div>
  </div>
</div>

<!-- Edit Task Modal -->
<div class="modal fade" id="editModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Edit Task</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <input type="hidden" id="edit_id">
        <label for="">Title</label>
        <input type="text" id="edit_title" class="form-control mb-2" placeholder="Title" required>
        <label for="">Description</label>
        <textarea id="edit_desc" class="form-control" placeholder="Description"></textarea>
        <label for="">Status</label>
        <select id="edit_status" class="form-control mt-2" value = >
            <option value="pending">Pending</option>
            <option value="completed">Completed</option>
        </select>
      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button class="btn btn-primary" onclick="updateTask()">Update</button>
      </div>

    </div>
  </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title text-danger">Delete Task</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <p>Are you sure you want to delete this task?</p>
        <input type="hidden" id="delete_id">
      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-danger" onclick="confirmDelete()">Delete</button>
      </div>

    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>

    function openAddModal() {
        $('#add_title').val('');
        $('#add_desc').val('');
        $('#add_status').val('pending');

        var modal = new bootstrap.Modal(document.getElementById('addModal'));
        modal.show();
    }

    function openEditModal(id, title, description, status) {
        $('#edit_id').val(id);
        $('#edit_title').val(title);
        $('#edit_desc').val(description);
        $('#edit_status').val(status);

        var modal = new bootstrap.Modal(document.getElementById('editModal'));
        modal.show();
    }

    function openDeleteModal(id) {
        $('#delete_id').val(id);

        var modal = new bootstrap.Modal(document.getElementById('deleteModal'));
        modal.show();
    }

    function renderPagination(pager) {
        let html = '';

        for (let i = 1; i <= pager.pageCount; i++) {
            html += `<button onclick="loadTasks(${i})" class="btn btn-sm btn-primary m-1">${i}</button>`;
        }

        $('#pagination').html(html);
    }

    function loadTasks(page = 1) {
        let search = $('#search').val();

        $.get(`http://localhost/smartTaskManagement/public/tasks?page=${page}&search=${search}`, function(res) {
            let html = '';
            let sno = 1;
             if (res.tasks.length === 0) {
                html = `<tr><td style = "text-align: center;" colspan="4">No Task Available</td></tr>`;
            } else {
                res.tasks.forEach(task => {
                    
                        html += `<tr>
                            <td>${sno}</td>
                            <td>${task.title}</td>
                            <td>${task.status}</td>
                            <td>
                                <button onclick="openEditModal(${task.id}, '${task.title}', '${task.description}', '${task.status}')" class="btn btn-warning btn-sm">Edit</button>
                                <button onclick="openDeleteModal(${task.id})" class="btn btn-danger btn-sm">Delete</button>
                            </td>
                        </tr>`;
                        sno++;
                    
                    
                });
            }
            $('#taskTable').html(html);

            // 👉 Pagination UI
            renderPagination(res.pager);
        });
    }

    function addTask() {
        let title = $('#add_title').val();

        if (!title) {
            toastr.error("Title is required");
            return;
        }

        $.post("http://localhost/smartTaskManagement/public/tasks", {
            title: $('#add_title').val(),
            description: $('#add_desc').val(),
            status: $('#add_status').val()
        }, function(res) {

            if (res.error) {
                toastr.error(res.error);
                return;
            }

            loadTasks();

            let modalEl = document.getElementById('addModal');
            let modal = bootstrap.Modal.getInstance(modalEl);
            modal.hide();

            toastr.success("Task added successfully");
        });
    }

    function updateTask() {
        let id = $('#edit_id').val();

        $.post(`http://localhost/smartTaskManagement/public/tasks/update/${id}`, {
            title: $('#edit_title').val(),
            description: $('#edit_desc').val(),
            status: $('#edit_status').val()
        }, function() {
            loadTasks();

            // Close modal
            let modalEl = document.getElementById('editModal');
            let modal = bootstrap.Modal.getInstance(modalEl);
            modal.hide();

            toastr.success("Task updated successfully");
        });
    }

    function confirmDelete() {
        let id = $('#delete_id').val();

        $.get(`http://localhost/smartTaskManagement/public/tasks/delete/${id}`, function() {
            loadTasks();

            // close modal
            let modalEl = document.getElementById('deleteModal');
            let modal = bootstrap.Modal.getInstance(modalEl);
            modal.hide();

            toastr.success("Task deleted successfully");
        });
    }

    function logout() {
        $.get("http://localhost/smartTaskManagement/public/logout", function() {
            toastr.success("Logged out successfully");

            setTimeout(() => {
                window.location.href = "/smartTaskManagement/public/login";
            }, 1000);
        });
    }

    loadTasks();

    $('#search').on('keyup', function() {
        loadTasks();
    });
</script>

</body>
</html>