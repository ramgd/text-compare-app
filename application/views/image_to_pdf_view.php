<!DOCTYPE html>
<html>
<head>
    <title>Image to PDF Maker</title>
    <style>
        body { font-family: Arial; background: #f7f7f7; }
        .header {
            background: linear-gradient(to right, #5b86e5, #36d1dc);
            padding: 18px;
            text-align: center;
            color: #fff;
            font-size: 22px;
            font-weight: bold;
            border-radius: 0 0 10px 10px;
        }
        .container {
            width: 60%;
            margin: 25px auto;
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 0 10px #ccc;
        }
        .upload-box {
            border: 2px dashed #aaa;
            padding: 25px;
            border-radius: 10px;
            text-align: center;
            background: #fafafa;
        }
        .preview img {
            width: 100%;
            border-radius: 8px;
        }
        .image-item {
            border: 1px solid #aaa;
            padding: 10px;
            margin-bottom: 10px;
            display: flex;
            gap: 10px;
            background: #f5f5f5;
            border-radius: 8px;
            cursor: move;
        }
        .image-item img {
            width: 120px;
            height: 90px;
            object-fit: cover;
            border-radius: 6px;
        }
        .btn {
            display: inline-block;
            padding: 10px 18px;
            background: #5b86e5;
            color: #fff;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            border: none;
        }
        .btn:hover {
            background: #477ae0;
        }
    </style>

    <!-- jQuery + Sortable -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>

</head>
<body>

<div class="header">Image to PDF Maker</div>

<div class="container">

    <div class="upload-box">
        <input type="file" id="imageInput" multiple accept="image/*">
        <p>Select multiple images</p>
    </div>

    <h3>Preview & Sort Images</h3>
    <div id="imageList"></div>

    <h3>Filters</h3>
    <button class="btn filter-btn" data-filter="bright">Bright</button>
    <button class="btn filter-btn" data-filter="warm">Warm</button>
    <button class="btn filter-btn" data-filter="white_doc">White Document</button>

    <h3>Orientation</h3>
    <label><input type="radio" name="orientation" value="P" checked> Vertical (Portrait)</label>
    <br>
    <label><input type="radio" name="orientation" value="L"> Horizontal (Landscape)</label>

    <h3>Rename PDF</h3>
    <input type="text" id="pdfName" class="form-control" placeholder="Enter PDF file name">

    <br><br>
    <button class="btn" id="createPdfBtn">Create PDF</button>

    <div id="result"></div>

</div>


<script>
// ============================================
// DISPLAY IMAGE PREVIEW
// ============================================
$("#imageInput").change(function(){
    $("#imageList").html(""); 
    [...this.files].forEach((file, index) => {
        let url = URL.createObjectURL(file);
        $("#imageList").append(`
            <div class="image-item" data-name="${file.name}">
                <img src="${url}">
                <div>
                    <b>${index+1}. ${file.name}</b>
                </div>
            </div>
        `);
    });

    // Enable sorting
    new Sortable(imageList, { animation:150 });
});


// ============================================
// APPLY FILTER
// ============================================
$(".filter-btn").click(function(){

    let filter = $(this).data('filter');
    // FILTER WILL APPLY AFTER UPLOAD (on server-side)
    alert("Filter will apply after PDF creation: " + filter);

});


// ============================================
// CREATE PDF
// ============================================
$("#createPdfBtn").click(function(){

    let pdfName = $("#pdfName").val();
    if(pdfName.trim() === ""){
        alert("Please enter PDF name");
        return;
    }

    let sorted = [];
    $(".image-item").each(function(){
        sorted.push($(this).data("name"));
    });

    $.post("image-to-pdf/create", {
        sorted_images: sorted,
        pdf_name: pdfName,
        orientation: $('input[name=orientation]:checked').val()
    }, function(res){
        let obj = JSON.parse(res);
        if(obj.status === "success"){
            $("#result").html(`<a class="btn" href="${obj.download}">Download PDF</a>`);
        }
    });
});
</script>

</body>
</html>
