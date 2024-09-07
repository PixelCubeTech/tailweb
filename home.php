<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';

$students = $conn->query("SELECT * FROM students")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Teacher Portal</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1>Welcome, <?php echo $_SESSION['username']; ?></h1>
            <div>
                <button class="btn btn-primary mr-3" onclick="showAddStudentModal()">
                    <i class="fas fa-plus"></i> Add Student
                </button>
                <a href="logout.php" class="btn btn-danger">
                    <i class="fas fa-sign-out-alt"></i> Sign Out
                </a>
            </div>
        </div>
        <table class="table table-bordered table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>Name</th>
                    <th>Subject</th>
                    <th>Marks</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="studentTableBody">
                <?php foreach ($students as $student): ?>
                    <tr>
                        <td contenteditable="true" data-id="<?php echo $student['id']; ?>" data-field="name" onblur="updateStudent(this)"><?php echo $student['name']; ?></td>
                        <td contenteditable="true" data-id="<?php echo $student['id']; ?>" data-field="subject" onblur="updateStudent(this)"><?php echo $student['subject']; ?></td>
                        <td contenteditable="true" data-id="<?php echo $student['id']; ?>" data-field="marks" onblur="updateStudent(this)"><?php echo $student['marks']; ?></td>
                        <td>
                            <button class="btn btn-danger" onclick="deleteStudent('<?php echo $student['id']; ?>')">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                            <button class="btn btn-warning" onclick="showEditModal('<?php echo $student['id']; ?>', '<?php echo $student['name']; ?>', '<?php echo $student['subject']; ?>', '<?php echo $student['marks']; ?>')">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Add Student Modal -->
        <div class="modal" id="addStudentModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Student</h5>
                        <button type="button" class="close" onclick="hideAddStudentModal()" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="addStudentForm" onsubmit="addStudent(event)">
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" class="form-control" id="name" required>
                            </div>
                            <div class="form-group">
                                <label>Subject</label>
                                <input type="text" class="form-control" id="subject" required>
                            </div>
                            <div class="form-group">
                                <label>Marks</label>
                                <input type="number" class="form-control" id="marks" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Add</button>
                            <button type="button" class="btn btn-secondary" onclick="hideAddStudentModal()">Cancel</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Student Modal -->
        <div class="modal" id="editStudentModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Student</h5>
                        <button type="button" class="close" onclick="hideEditModal()" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="editStudentForm" onsubmit="updateStudentDetails(event)">
                            <input type="hidden" id="editStudentId" name="id">
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" class="form-control" id="editName" name="name" required>
                            </div>
                            <div class="form-group">
                                <label>Subject</label>
                                <input type="text" class="form-control" id="editSubject" name="subject" required>
                            </div>
                            <div class="form-group">
                                <label>Marks</label>
                                <input type="number" class="form-control" id="editMarks" name="marks" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Update</button>
                            <button type="button" class="btn btn-secondary" onclick="hideEditModal()">Cancel</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
    function showAddStudentModal() {
        $('#addStudentModal').modal('show');
    }

    function hideAddStudentModal() {
        $('#addStudentModal').modal('hide');
    }

    function addStudent(event) {
        event.preventDefault();
        const name = document.getElementById('name').value;
        const subject = document.getElementById('subject').value;
        const marks = document.getElementById('marks').value;

        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'add_student.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                location.reload();
            } else {
                alert('Failed to add student');
            }
        };
        xhr.send(`name=${name}&subject=${subject}&marks=${marks}`);
    }

    // function updateStudent(element) {
    //     const id = element.getAttribute('data-id');
    //     const field = element.getAttribute('data-field');
    //     const value = element.textContent.trim(); // Ensure value is trimmed of any unnecessary whitespace

    //     const xhr = new XMLHttpRequest();
    //     xhr.open('POST', 'update_student.php', true);
    //     xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    //     xhr.onload = function() {
    //         if (xhr.status !== 200) {
    //             alert('Failed to update student');
    //         }
    //     };
    //     xhr.send(`id=${id}&field=${field}&value=${value}`);
    // }

    function deleteStudent(id) {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'delete_student.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                location.reload();
            } else {
                alert('Failed to delete student');
            }
        };
        xhr.send(`id=${id}`);
    }

    function showEditModal(id, name, subject, marks) {
        document.getElementById('editStudentId').value = id;
        document.getElementById('editName').value = name;
        document.getElementById('editSubject').value = subject;
        document.getElementById('editMarks').value = marks;
        $('#editStudentModal').modal('show');
    }

    function hideEditModal() {
        $('#editStudentModal').modal('hide');
    }

    function updateStudentDetails(event) {
        event.preventDefault();
        const id = document.getElementById('editStudentId').value;
        const name = document.getElementById('editName').value;
        const subject = document.getElementById('editSubject').value;
        const marks = document.getElementById('editMarks').value;

        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'update_student.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                location.reload();
            } else {
                alert('Failed to update student');
            }
        };
        xhr.send(`id=${id}&name=${name}&subject=${subject}&marks=${marks}`);
    }
</script>

</body>
</html>
