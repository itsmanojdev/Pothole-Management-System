/***********************
 * Internet Connection Check *
 **********************/
const checkOnlineStatus = async () => {
    try {
        const res = await fetch("/ping", {
            method: "GET",
            cache: "no-store",
        });

        return navigator.onLine && res.ok;
    } catch (err) {
        console.log("Ping error", err);
        return false;
    }
};

window.addEventListener("load", async () => {
    (await checkOnlineStatus()) ||
        toast("error", "Internet Connection Required");
});

setInterval(async () => {
    let result = await checkOnlineStatus();
    result || toast("error", "Internet Connection Required");
}, 30000);

window.checkOnlineStatus = checkOnlineStatus;

/***********************
 * Toast Message Handler *
 **********************/
const toast = (type, message) => {
    switch (type) {
        case "success":
            document
                .querySelector("#toast-success")
                .play()
                .catch((err) => console.log("success audio err", err));
            toastr.success(message);
            break;
        case "error":
            document
                .querySelector("#toast-error")
                .play()
                .catch((err) => console.log("error audio err", err));
            toastr.error(message);
            break;
        case "info":
            toastr.info(message);
            break;
        default:
            break;
    }
};

window.toast = toast;

/***********************
 * Convert Kebab/Snake Case To Title Case *
 **********************/
const titleCase = (str, strCase = "kebab") => {
    let seperator = "-";
    if (strCase == "snake") {
        seperator = "_";
    } else if (strCase != "kebab") {
        console.log("Invalid Type. Function: titleCase");
        return str;
    }
    return str
        .toLowerCase()
        .split(seperator)
        .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
        .join(" ");
};

/***********************
 * Image File Preview Handler - Called When Changing File Input *
 **********************/
const updateImagePreview = (event) => {
    const input = event.target;
    const name = input.name;
    const title = titleCase(name);

    const preview = document.querySelector(`#${name}-preview`);

    //Remove child of preview if any
    while (preview.firstChild) {
        preview.removeChild(preview.firstChild);
    }

    //Function for setting Default Image
    const setDefaultImg = () => {
        image.src = input.imgSrc;
        image.alt = image.title = title;
    };

    //Add uploaded file to the preview if exists & valid
    const image = document.createElement("img");
    image.classList.add("image-cover", "rounded-lg");

    const curFile = input.files[0];
    fileCheck: if (curFile == undefined) {
        setDefaultImg();
        toast("info", "No files currently selected for upload");
    } else {
        // Size Check -> upload file size limit - 5MB
        if(curFile.size > 5*1024*1024){
            setDefaultImg();
            toast(
                "error",
                `File name ${curFile.name}: Exceeds the limit (5MB)`,
            );
            break fileCheck;
        }

        // Type Check -> JPEG, JPG, PNG
        if (validateFileType(CONSTANTS.FILE_TYPE.IMAGE, curFile.type)) {
            image.src = URL.createObjectURL(curFile);
            image.alt = image.title = title;
        } else {
            setDefaultImg();
            toast(
                "error",
                `File name ${curFile.name}: Not a valid file type. Choose .jpeg, .jpg, .png`,
            );
        }
    }
    preview.appendChild(image);
};

window.updateImagePreview = updateImagePreview;

/***********************
 * File Type Validator *
 **********************/
const validateFileType = (type, fileType) => {
    switch (type) {
        case CONSTANTS.FILE_TYPE.IMAGE:
            return CONSTANTS.IMAGE_FILE_TYPES.includes(fileType);
        default:
            console.log("Invalid File Type, func-validFileType");
            return false;
    }
};

window.validFileType = validateFileType;

/***********************
 * Update form Request *
 **********************/
// const updateFormRequest = (formId, URL) => {
//     const form = document.getElementById(formId);

//     // Store initial values
//     const initialData = {};

//     //All input except submit btn and file
//     form.querySelectorAll(
//         'input:not([type="submit"]):not([type="file"])',
//     ).forEach((input) => {
//         initialData[input.name] = input.value;
//     });

//     form.addEventListener("submit", async (e) => {
//         e.preventDefault();

//         const formData = new FormData(form);
//         let hasChanges = false;

//         //All input except submit btn and file
//         form.querySelectorAll(
//             'input:not([type="submit"]):not([type="file"])',
//         ).forEach((input) => {
//             // Compare current data with initial data
//             if (input.value !== initialData[input.name]) {
//                 formData.append(input.name, input.value);
//                 hasChanges = true;
//             }
//         });

//         //File inputs
//         form.querySelectorAll('input[type="file"]').forEach((fileInput) => {
//             if (fileInput.files.length > 0) {
//                 formData.append(fileInput.name, fileInput.files[0]);
//                 hasChanges = true;
//             }
//         });

//         // Send only changed fields via fetch
//         try {
//             const response = await fetch(URL, {
//                 method: "PATCH",
//                 body: formData,
//             });
//         } catch (e) {
//             console.error(e);
//         }
//     });
// };

// window.updateFormRequest = updateFormRequest;
