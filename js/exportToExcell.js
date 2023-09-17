// function exportTableToExcel() {
//   const table = document.getElementById("table-to-export");
//   const rows = table.querySelectorAll("tr");
//   let csvData = [];

//   // Iterate through rows and cells to construct CSV data
//   for (let i = 0; i < rows.length; i++) {
//     const row = [];
//     const cells = rows[i].querySelectorAll("td, th");

//     for (let j = 0; j < cells.length; j++) {
//       row.push(cells[j].innerText);
//     }

//     csvData.push(row.join(","));
//   }

//   // Create a data URI for Excel file
//   const csvContent = "data:text/csv;charset=utf-8," + csvData.join("\n");
//   const encodedUri = encodeURI(csvContent);

//   // Create a temporary anchor element to trigger the download
//   const link = document.createElement("a");
//   link.setAttribute("href", encodedUri);
//   link.setAttribute("download", "table-export.csv");
//   document.body.appendChild(link);

//   // Trigger the click event on the anchor element to initiate the download
//   link.click();

//   // Clean up
//   document.body.removeChild(link);
// }

// document
//   .getElementById("export-button")
//   .addEventListener("click", exportTableToExcel);
// Import the xlsx library
// Require the xlsx library
// Require the xlsx library at the top of your module

// Rest of your code
// function exportTableToExcel() {
//   const table = document.getElementById("table-to-export");
//   const rows = table.querySelectorAll("tr");
//   console.log(rows);
//   // Create a new workbook
//   const workbook = XLSX.utils.book_new();

//   // Create a worksheet and add it to the workbook
//   const worksheet = XLSX.utils.aoa_to_sheet([]);

//   // Iterate through rows and cells to populate the worksheet
//   const data = [];
//   rows.forEach((row) => {
//     const rowData = [];
//     const cells = row.querySelectorAll("td, th");

//     cells.forEach((cell) => {
//       rowData.push(cell.innerText);
//     });

//     data.push(rowData);
//   });

//   XLSX.utils.sheet_add_aoa(worksheet, data);

//   // Add the worksheet to the workbook
//   XLSX.utils.book_append_sheet(workbook, worksheet, "Sheet 1");

//   worksheet["!cols"] = [{ width: 15 }, { width: 15 }]; // Adjust column widths as needed

//   // Create a blob from the workbook and trigger a download
//   XLSX.writeFile(workbook, "table-export.xlsx");
// }

// document
//   .getElementById("export-button")
//   .addEventListener("click", exportTableToExcel);
function exportTableToExcel() {
  const table = document.getElementById("table-to-export");
  const rows = table.querySelectorAll("tr");

  // Create a new workbook
  const workbook = XLSX.utils.book_new();

  // Create a worksheet and add it to the workbook
  const worksheet = XLSX.utils.aoa_to_sheet([]);

  // Keep track of merged cells
  const merges = [];

  // Iterate through rows and cells to populate the worksheet
  const data = [];
  rows.forEach((row) => {
    const rowData = [];
    const cells = row.querySelectorAll("td, th");

    cells.forEach((cell) => {
      const cellData = cell.innerText;
      const rowspan = cell.getAttribute("rowspan");
      const colspan = cell.getAttribute("colspan");

      // Check if the cell has a rowspan attribute
      if (rowspan && parseInt(rowspan) > 1) {
        // If rowspan is greater than 1, calculate the number of rows it spans
        const rowspanValue = parseInt(rowspan);
        // Calculate the number of columns it spans (default to 1 if colspan is not specified)
        const colspanValue = colspan ? parseInt(colspan) : 1;

        // Merge the corresponding cells in the Excel worksheet
        merges.push({
          s: { r: data.length, c: rowData.length },
          e: {
            r: data.length + rowspanValue - 1,
            c: rowData.length + colspanValue - 1,
          },
        });

        // Add empty data for the merged cells
        for (let i = 0; i < rowspanValue; i++) {
          const rowArray = [];
          for (let j = 0; j < colspanValue; j++) {
            rowArray.push("");
          }
          rowData.push(...rowArray);
        }
      } else {
        // Add regular cell data
        rowData.push(cellData);
      }
    });

    data.push(rowData);
  });

  XLSX.utils.sheet_add_aoa(worksheet, data);

  // Apply the merges to the worksheet
  worksheet["!merges"] = merges;

  // Add the worksheet to the workbook
  XLSX.utils.book_append_sheet(workbook, worksheet, "Sheet 1");

  // Create a blob from the workbook and trigger a download
  XLSX.writeFile(workbook, "table-export.xlsx");
}

document
  .getElementById("export-button")
  .addEventListener("click", exportTableToExcel);
