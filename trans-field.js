const fs = require("fs");
const glob = require("glob");

// Define the directory where you want to perform the replacement
const directoryPath = "./src"; // Update this with your source directory

// Function to convert a string to Camel Case
function toCamelCase(input) {
  return input
    .split(/[^a-zA-Z0-9]+/)
    .map((word, index) => {
      if (index === 0) {
        // Keep the first word as is
        return word.toLowerCase();
      } else {
        // Capitalize the first character of each word
        return word.charAt(0).toUpperCase() + word.slice(1).toLowerCase();
      }
    })
    .join("");
}

// Function to replace the pattern in a file
function replacePatternInFile(filePath) {
  let content = fs.readFileSync(filePath, "utf8");
  const pattern = /<base-label required>(.*?)<\/base-label>/g; // Matches <base-label>Any Text</base-label>
  content = content.replace(pattern, (match, value) => {
    if (value.startsWith("{{")) {
      return match;
    }
    const camelCaseKey = toCamelCase(value);
    const replacement = `<base-label required>{{ $t("label.${camelCaseKey}") }}</base-label>`;
    // console.log(`Replaced: ${match} => ${replacement}`);
    console.log(`label.${camelCaseKey},"${value}"`);
    return replacement;
  });
  fs.writeFileSync(filePath, content, "utf8");
}

// Function to replace the pattern in all matching files
function replacePatternInFiles(directoryPath) {
  glob(`${directoryPath}/**/*.vue`, (err, files) => {
    if (err) {
      console.error("Error reading files:", err);
      return;
    }
    files.forEach((file) => {
      replacePatternInFile(file);
    });
  });
}

// Start the replacement process
replacePatternInFiles(directoryPath);
