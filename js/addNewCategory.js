const modal = document.getElementById("categoryModal");
const addBtn = document.querySelector(".add-category-btn");
const closeBtn = document.querySelector(".close-modal");

// Open Modal
addBtn.onclick = () => modal.style.display = "block";

// Close Modal
closeBtn.onclick = () => modal.style.display = "none";
window.onclick = (event) => { if (event.target == modal) modal.style.display = "none"; }

// Save Logic
document.getElementById("saveCategoryBtn").onclick = function() {
    const categoryName = document.getElementById("newCategoryName").value;
    
    if(!categoryName) {
        alert("Please enter a category name.");
        return;
    }

    const deviceData = [];
    const rows = document.querySelectorAll(".modal-table tbody tr");
    
    rows.forEach(row => {
        const inputs = row.querySelectorAll("input");
        const ip = inputs[0].value.trim();
        const name = inputs[1].value.trim();
        
        if (ip !== "" || name !== "") {
            deviceData.push({ ip: ip, name: name });
        }
    });

    fetch('saveNewCategory.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            name: categoryName,
            devices: deviceData
        })
    })
    .then(response => response.text())
    .then(data => {
        if(data === "success") {
            alert("New category added successfuly");            
            sessionStorage.removeItem("ipStatusRegistry");
            location.reload();
        } else {
            alert("Error saving category.");
        }
    });
};

document.getElementById("addRowBtn").onclick = function() {
    const tableBody = document.querySelector(".modal-table tbody");
    const newRow = document.createElement("tr");
    
    newRow.innerHTML = `
        <td><input type="text" placeholder="0.0.0.0"></td>
        <td><input type="text" placeholder="New Device"></td>
    `;
    
    tableBody.appendChild(newRow);
    
    // Auto-focus the first input of the new row
    newRow.querySelector("input").focus();
};