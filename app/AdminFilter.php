<?php

namespace App;

enum AdminFilter: string
{
    case OPEN_POTHOLES = 'Open Potholes';
    case ASSIGNED_TO_ME = 'Assigned To Me';
}
