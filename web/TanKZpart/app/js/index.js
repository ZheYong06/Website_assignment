(function () {
    emailjs.init("FfRP8_grXtjwuFmX2");
})();

document.getElementById("emailSubmit").addEventListener("click", function () {
    document.getElementById("layerEM1").style.display = "block";
});

document.getElementById("PhoneNumberSubmit").addEventListener("click", function () {
    document.getElementById("layerPN1").style.display = "block";
});

document.getElementById("comfirmNoEmail").addEventListener("click", function (event) {
    event.preventDefault();
    document.getElementById("layerEM1").style.display = "none";
});

document.getElementById("comfirmNoPN").addEventListener("click", function (event) {
    event.preventDefault();
    document.getElementById("layerPN1").style.display = "none";
});

document.getElementById("comfirmYesEmail").addEventListener("click", function (event) {
    let emailInput = document.getElementById("emailInput").value;
    let emailFormat = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    let NewEmailName = "";
    let OTPEMAIL = "";
    if (!emailFormat.test(emailInput)) {
        alert("Invalid email format! \n Format: name@gmail.com");
        event.preventDefault();
        return
    } else {
        document.getElementById("emailOTP").style.display = "block";
        NewEmailName = document.getElementById("emailInput").value
        OTPEMAIL = Math.floor(1000 + Math.random() * 9000);
        var templateParams = { to_email: emailInput };
        emailjs.send("Web_ASS", "template_ca0ly36", {
            to_email: String(emailInput),
            otp_code: OTPEMAIL
        }).then(response => {
            alert("OTP has been send to your email, please check it!");
        }).catch(error => {
            console.error("try again", error);
        });
        document.getElementById("OTPbutton").addEventListener("click", function () {
            let VerifirdOTP = parseInt(document.getElementById("OTP").value, 10)
            if (VerifirdOTP !== OTPEMAIL) {
                event.preventDefault();
                alert("Your OTP is invalid, try again !");
            }
            else {
                alert("Your email has been change");
                document.getElementById("emailOTP").style.display = "none";
                document.getElementById("layerEM1").style.display = "none";
                setTimeout(function () {
                    location.reload();
                }, 500);
            }
        })
    }
});

document.getElementById("comfirmYesPN").addEventListener("click", function (event) {
    let phonenumberInput = document.getElementById("phonenumberInput").value.trim();
    let phonenumberFormat = /^[0-9]{3}-[0-9]{7}$/;
    let phonenumberFormat2 = /^[0][0-9]{8,9}$/;
    let phonenumberFormat3 = /^\+60\d{9,10}$/;
    if (phonenumberFormat.test(phonenumberInput) || phonenumberFormat2.test(phonenumberInput) || phonenumberFormat3.test(phonenumberInput)) {
        document.getElementById("layerPN1").style.display = "none";
        setTimeout(function () {
            location.reload();
        }, 500);
    } else {
        alert("Invalid phone number format! \n Format: 123-4567890  |  1234567890  |  234567890");
        event.preventDefault();
        return
    }
});

document.getElementById("imageupload").addEventListener("change", function () {
    document.getElementById("uploadimg").submit();
});

document.getElementById("savebutton").addEventListener("click", function () {
    setTimeout(function () {
        location.reload();
    }, 500);
})

document.getElementById("imageupload").addEventListener("change", function () {
    setTimeout(function () {
        location.reload();
    }, 500);
})