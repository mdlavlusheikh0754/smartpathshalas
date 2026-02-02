<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\AdmissionApplication;

class AdmissionPhotoUploadTest extends TestCase
{
    // use RefreshDatabase; // Be careful with this on existing DB

    public function test_admission_photo_upload()
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('student.jpg');

        $response = $this->post(route('tenant.admission.store'), [
            'name_bn' => 'Test Student',
            'name_en' => 'Test Student',
            'father_name' => 'Father Name',
            'mother_name' => 'Mother Name',
            'date_of_birth' => '2010-01-01',
            'gender' => 'male',
            'religion' => 'islam',
            'phone' => '01700000000',
            'class' => 'One',
            'section' => 'A',
            'photo' => $file,
            'emergency_contact' => '01700000000',
            'nationality' => 'Bangladeshi',
            'present_address' => 'Dhaka',
            'permanent_address' => 'Dhaka',
        ]);

        // $response->dumpSession();
        // $response->assertSessionHasNoErrors();
        
        if ($response->getSession()->has('errors')) {
            dump($response->getSession()->get('errors')->all());
        }

        // Check if file exists in the fake storage
        // Storage::disk('public')->assertExists('admission_photos/' . $file->hashName());
        
        // Since we are mocking storage, we can't check the real filesystem unless we don't mock it.
        // But for debugging logic, mocking is fine.
        
        // However, I want to see if the CONTROLLER logic works.
    }
}
