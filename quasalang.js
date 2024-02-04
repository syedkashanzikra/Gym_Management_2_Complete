const fs = require("fs");
const csv = require("csv-parser");

// Create an object to store the translations
const translations = {};

// Read CSV data from the file "translations.csv" using csv-parser
fs.createReadStream("translations.csv")
  .pipe(csv())
  .on("data", (row) => {
    // Process each row
    const key = row["Key"];
    for (const [header, value] of Object.entries(row)) {
      if (header !== "Key") {
        const [languageLabel, languageCode] = header
          .split(",")
          .map((item) => item.trim());

        // Split the key into parts to build the nested structure
        const keyParts = key.split(".");
        let currentObj =
          translations[languageCode] || (translations[languageCode] = {});

        for (const part of keyParts.slice(0, -1)) {
          currentObj = currentObj[part] || (currentObj[part] = {});
        }
        if (value.trim()) {
          currentObj[keyParts.slice(-1)[0]] = value.trim();
        }
      }
    }
  })
  .on("end", () => {
    // Generate individual language files
    for (const languageCode in translations) {
      if (translations.hasOwnProperty(languageCode)) {
        const dirPath = `src/i18n/${languageCode}`;
        const filePath = `${dirPath}/index.js`;
        const languageData = JSON.stringify(
          translations[languageCode],
          null,
          2
        );
        const fileContent = `export default ${languageData};\n`;

        // Check if the directory exists, and create it if it doesn't
        if (!fs.existsSync(dirPath)) {
          fs.mkdirSync(dirPath, { recursive: true });
        }

        // Write the content to the file
        fs.writeFileSync(filePath, fileContent);
        console.log(`Generated ${filePath}`);
      }
    }

    // Generate src/i18n/index.js
    const indexFilePath = "src/i18n/index.js";
    const importStatements = Object.keys(translations)
      .map((languageCode) => {
        // Replace hyphens with empty strings in import statement
        const normalizedLanguageCode = languageCode.replace("-", "");
        return `import ${normalizedLanguageCode} from "./${languageCode}";`;
      })
      .join("\n");

    const exportStatement = `export default {\n${Object.keys(translations)
      .map((languageCode) => {
        const normalizedLanguageCode = languageCode.replace("-", "");
        return `  "${languageCode}": ${normalizedLanguageCode},`;
      })
      .join("\n")}\n};`;

    const indexFileContent = `${importStatements}\n\n${exportStatement}\n`;

    // Write the content to the index file
    fs.writeFileSync(indexFilePath, indexFileContent);
    console.log(`Generated ${indexFilePath}`);

    console.log("Files generated successfully.");
  });
