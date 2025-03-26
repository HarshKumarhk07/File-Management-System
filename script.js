document.getElementById('uploadBtn').addEventListener('click', function () {
    let fileInput = document.getElementById('fileUpload');
    let formData = new FormData();

    // 🛠 Ensure a file is selected before uploading
    if (fileInput.files.length === 0) {
        alert("❌ Please select a file!");
        return;
    }

    let file = fileInput.files[0]; // Get the selected file
    formData.append('fileUpload', file);

    console.log("📂 Selected File:", file); // Debugging

    fetch('http://localhost/php/project/OS_PROJECT/upload.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json()) // Expect JSON response
    .then(data => {
        console.log("📩 Server Response:", data);
        if (data.error) {
            alert("❌ " + data.error);
        } else {
            alert("✅ " + data.success);
            loadFiles(); // Reload file list after upload
        }
    })
    .catch(error => console.error('❌ Upload Error:', error));
});

function loadFiles() {
    fetch("http://localhost/php/project/OS_PROJECT/list_files.php")
        .then(response => response.json())
        .then(files => {
            let fileList = document.getElementById("fileList");
            let previewList = document.getElementById("previewList");
            let trashList = document.getElementById("trashList");

            fileList.innerHTML = "";
            previewList.innerHTML = "";
            trashList.innerHTML = "";

            files.forEach((file, index) => {
                let row = document.createElement("tr");
                let fileType = file.file_name.split('.').pop().toLowerCase();

                // ✅ Display Image Preview for Images
                let previewContent = (["jpg", "jpeg", "png", "gif"].includes(fileType))
                    ? `<img src="uploads/${file.file_name}" width="80">`
                    : `<span>${file.file_name}</span>`;

                row.innerHTML = `
                    <td>${index + 1}</td>
                    <td>${previewContent}</td>
                    <td>
                        ${file.status === "active" 
                            ? `<button class="delete-btn" onclick="deleteFile('${file.file_name}')">Delete</button>` 
                            : `<button class="recover-btn" onclick="recoverFile('${file.file_name}')">Recover</button>`}
                    </td>
                `;

                if (file.status === "active") {
                    fileList.appendChild(row);

                    // ✅ Show preview button only for active files
                    let previewRow = document.createElement("tr");
                    previewRow.innerHTML = `
                        <td>${index + 1}</td>
                        <td>${previewContent}</td>
                        <td><button class="preview-btn" onclick="previewFile('${file.file_name}')">Preview</button></td>
                    `;
                    previewList.appendChild(previewRow);
                } else {
                    trashList.appendChild(row);
                }
            });
        })
        .catch(error => console.error("❌ Error fetching files:", error));
}


function previewFile(fileName) {
    window.open(`preview.html?file=${encodeURIComponent(fileName)}`, "_blank");
}

function deleteFile(fileName) {
    fetch('delete.php', {
        method: 'POST',
        body: new URLSearchParams({ fileName })
    }).then(response => response.text()).then(data => {
        alert(data);
        loadFiles();
    }).catch(error => console.error("Error deleting file:", error));
}

function recoverFile(fileName) {
    fetch('recover.php', {
        method: 'POST',
        body: new URLSearchParams({ fileName })
    }).then(response => response.text()).then(data => {
        alert(data);
        loadFiles();
    }).catch(error => console.error("Error recovering file:", error));
}

function toggleMode() {
    document.body.classList.toggle('dark-mode');
    localStorage.setItem('darkMode', document.body.classList.contains('dark-mode') ? 'enabled' : 'disabled');
}

document.addEventListener("DOMContentLoaded", () => {
    if (localStorage.getItem('darkMode') === 'enabled') {
        document.body.classList.add('dark-mode');
    }
    loadFiles();
});
