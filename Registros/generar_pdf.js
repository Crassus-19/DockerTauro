const PDFDocument = require("pdfkit");
const fs = require("fs");
const path = require("path");

// Verificar si se proporcionó el archivo JSON
if (process.argv.length < 3) {
  console.error("❌ Error: No se proporcionó un archivo JSON.");
  process.exit(1);
}

const dataPath = process.argv[2];

// Verificar si el archivo JSON existe
if (!fs.existsSync(dataPath)) {
  console.error(`❌ Error: El archivo JSON no existe en la ruta: ${dataPath}`);
  process.exit(1);
}

// Leer datos del JSON
let row;
try {
  row = JSON.parse(fs.readFileSync(dataPath, "utf8"));
} catch (error) {
  console.error("❌ Error al leer el archivo JSON:", error.message);
  process.exit(1);
}

console.log("✅ Datos cargados correctamente:", row);

// Ruta del archivo de salida
const outputFilePath = "/var/www/html/Registros/registro.pdf"; // Ruta dentro del contenedor


// Crear documento PDF
try {
  const doc = new PDFDocument({ size: "A4" });
  doc.pipe(fs.createWriteStream(outputFilePath));

  // Ruta del logo (marca de agua)
  const logoPath = "logo_fletes_tauro.png";

  // Función para escribir contenido en el PDF con desplazamiento vertical (yOffset)
  function escribirContenido(yOffset) {
    // Agregar la marca de agua si el archivo existe
    if (fs.existsSync(logoPath)) {
      doc.save();
      doc.fillOpacity(0.2); // Transparencia de la marca de agua
      doc.image(logoPath, 100, 180 + yOffset, { width: 400, align: "center" }); // Ajustar tamaño y posición
      doc.restore();
    }

    // Contenido del PDF
    doc.font("Helvetica-Bold").fontSize(20).text("Reporte de Taller", { align: "center" });
    doc.moveTo(50, 100 + yOffset).lineTo(550, 100 + yOffset).stroke();

    doc.fontSize(12).text(`Fecha: ${row.Fecha_Registro}`, 50, 120 + yOffset, { align: "left" });
    doc.text(`Folio: ${row.ID}`, 450, 120 + yOffset, { align: "right" });

    doc.moveDown(1);
    doc.font("Helvetica-Bold").text("Unidad:", 50, doc.y, { align: "left" });
    doc.font("Helvetica").text(row.Unidad, 150, doc.y - 15);

    doc.font("Helvetica-Bold").text("Uso:", 50, doc.y, { align: "left" });
    doc.font("Helvetica").text(row.Uso, 150, doc.y - 15);

    doc.font("Helvetica-Bold").text("KM / HORAS:", 50, doc.y, { align: "left" });
    doc.font("Helvetica").text(row.Cantidad, 150, doc.y - 15);

    doc.font("Helvetica-Bold").text("Tipo de Orden:", 50, doc.y, { align: "left" });
    doc.font("Helvetica").text(row.Tipo_Orden, 150, doc.y - 15);

    doc.font("Helvetica-Bold").text("Quién Reporta:", 50, doc.y, { align: "left" });
    doc.font("Helvetica").text(row.Reportado_Por, 150, doc.y - 15);

    doc.moveDown(1);
    doc.rect(50, doc.y, 500, 80).stroke();
    doc.font("Helvetica-Bold").text("Comentarios:", 60, doc.y + 10, { align: "left" });
    doc.font("Helvetica").text(row.Comentarios, 60, doc.y + 10);

    // Línea de firma
    doc.moveDown(2);
    doc.moveTo(200, doc.y + 30).lineTo(400, doc.y + 30).stroke();
    doc.font("Helvetica-Bold").fontSize(12).text("Firma", 50, doc.y + 40, { align: "center" });
    doc.moveDown(8);
  }

  // Escribir contenido en dos posiciones en la misma página con más separación
  escribirContenido(0);   // Primera copia en la parte superior
  escribirContenido(400); // Segunda copia más abajo para evitar solapamiento

  doc.end();
  console.log(`✅ PDF generado correctamente en: ${outputFilePath}`);
} catch (error) {
  console.error("❌ Error al generar el PDF:", error.message);
  process.exit(1);
}
