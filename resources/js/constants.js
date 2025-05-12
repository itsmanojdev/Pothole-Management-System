const APP_CONSTANTS = {
    //Valid image type
    IMAGE_FILE_TYPES: ["image/jpeg", "image/jpg", "image/png"],

    //File type
    FILE_TYPE: {
        IMAGE: "Image",
    },

    POTHOLE_STATUS: {
        OPEN: "Open",
    },

    //Admin Filters In Pothole Insex Page
    ADMIN_FILTERS: {
        OPEN_POTHOLES: "open-potholes",
        ASSIGNED_TO_ME: "assigned-to-me",
    },
};

window.CONSTANTS = Object.freeze(APP_CONSTANTS);
