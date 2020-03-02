<?php

namespace App\Form\Contact;

class ContactDtoExportType extends ContactDtoTypeAbstract
{

    public function getBlockPrefix()
    {
        return 'contact_export';
    }

}
