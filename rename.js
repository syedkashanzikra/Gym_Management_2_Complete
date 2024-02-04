const fs = require("fs");
const path = require("path");

const directory = "src/components/order";

fs.readdir(directory, (err, files) => {
  if (err) {
    console.error("Error reading directory:", err);
    return;
  }

  files.forEach((file) => {
    if (file.endsWith(".vue")) {
      const oldFilePath = path.join(directory, file);

      // Convert to PascalCase
      const pascalCaseName = file
        .split("-")
        .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
        .join("")
        .replace(".vue", "");

      const newFilePath = path.join(directory, pascalCaseName + ".vue");

      fs.rename(oldFilePath, newFilePath, (err) => {
        if (err) {
          console.error(`Error renaming ${file}:`, err);
        } else {
          console.log(`Renamed ${file} to ${pascalCaseName}.vue`);
        }
      });
    }
  });
});
