$(document).ready(function() {
    // Load tasks on page load
    loadTasks();

    // Add task
    $('#add-task-form').on('submit', function(e) {
        e.preventDefault();
        const task = $('#task').val();
        $.ajax({
            type: 'POST',
            url: 'task_manager.php',
            data: { action: 'add', task: task },
            success: function(response) {
                const res = JSON.parse(response);
                if (res.status === 'success') {
                    loadTasks();
                    $('#task').val('');
                } else {
                    alert(res.message);
                }
            }
        });
    });

    // Delete task
    $('#task-list').on('click', '.delete-task', function() {
        const taskId = $(this).data('id');
        $.ajax({
            type: 'POST',
            url: 'task_manager.php',
            data: { action: 'delete', id: taskId },
            success: function(response) {
                const res = JSON.parse(response);
                if (res.status === 'success') {
                    loadTasks();
                } else {
                    alert(res.message);
                }
            }
        });
    });

    // Load tasks function
    function loadTasks() {
        $.ajax({
            url: 'task_manager.php',
            data: { action: 'list' },
            success: function(response) {
                const res = JSON.parse(response);
                if (res.status === 'success') {
                    const tasks = res.tasks;
                    let taskList = '';
                    tasks.forEach(task => {
                        taskList += `<li class="list-group-item d-flex justify-content-between align-items-center">
                            ${task.task}
                            <button class="btn btn-danger btn-sm delete-task" data-id="${task.id}">Delete</button>
                        </li>`;
                    });
                    $('#task-list').html(taskList);
                } else {
                    alert(res.message);
                }
            }
        });
    }
});
