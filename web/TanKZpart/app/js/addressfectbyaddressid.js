document.getElementById("newaddres").addEventListener("click", function () {
    document.getElementById("layerNA1").style.display = "block";
})

document.getElementById("savebutton").addEventListener("click", function () {
    document.getElementById("layerNA1").style.display = "none";
})


document.getElementById("savebuttonbyADDid").addEventListener("click", function(event) {
    event.preventDefault(); // 阻止表单的默认提交行为

    // 手动提交表单
    document.querySelector("form").submit(); 

    // 延迟跳转到指定页面（1秒可以根据需要调整）
    setTimeout(function() {
        window.location.href = "program/address.php";
    }, 1000); // 延迟1秒跳转
})

document.getElementById("cancelbutton2").addEventListener("click", function () {
    document.getElementById("layerNA1").style.display = "none";
})



// 使用 .edit 选择器来选取所有具有 edit 类的按钮
document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll(".edit").forEach(button => {
        button.addEventListener("click", function () {
            // 获取按钮的 data-id 属性，作为地址 ID
            const addressId = button.getAttribute("data-addid");
         
            // 打开编辑层
            document.getElementById("layerA1").style.display = "block";
            
        });
    });
})

