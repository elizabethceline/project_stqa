<?php

namespace Tests\Unit;

use App\Http\Controllers\CustomerController;
use App\Models\Customer;
use Illuminate\Http\Request;
use Mockery;
use Tests\TestCase;

class CustomerControllerTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function it_shows_customer_profile()
    {
        session()->put('customer', 1);

        $customerMock = Mockery::mock('alias:' . Customer::class);
        $customerMock->shouldReceive('where')
            ->once()
            ->with('id', 1)
            ->andReturnSelf();
        $customerMock->shouldReceive('first')
            ->once()
            ->andReturn((object) ['id' => 1, 'name' => 'John Doe', 'email' => 'johndoe@example.com']);

        $controller = new CustomerController();
        $response = $controller->showProfile();

        $this->assertEquals('user.profile', $response->name());
        $this->assertArrayHasKey('customer', $response->getData());
        $this->assertEquals('John Doe', $response->getData()['customer']->name);
    }

    /** @test */
    public function it_fails_to_create_customer_with_missing_data()
    {
        $request = Request::create('/customers', 'POST', [
            'name' => '',
            'email' => 'invalid-email',
            'password' => '',
            'bio' => 'A short bio.',
        ]);

        $validatorMock = Mockery::mock('alias:Illuminate\Support\Facades\Validator');
        $validatorMock->shouldReceive('make')
            ->once()
            ->with(Mockery::type('array'), Mockery::type('array'), Mockery::type('array'), Mockery::type('array'))
            ->andReturnSelf();
        $validatorMock->shouldReceive('fails')->once()->andReturn(true);
        $validatorMock->shouldReceive('errors->first')->once()->andReturn('Name is required.');

        $controller = new CustomerController();
        $response = $controller->create($request);

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('Name is required.', session('error'));
    }

    /** @test */
    public function it_fails_to_create_customer_with_invalid_email()
    {
        $request = Request::create('/customers', 'POST', [
            'name' => 'Budi',
            'email' => 'invalid-email',
            'password' => '',
            'bio' => 'bio.',
        ]);

        $validatorMock = Mockery::mock('alias:Illuminate\Support\Facades\Validator');
        $validatorMock->shouldReceive('make')
            ->once()
            ->with(Mockery::type('array'), Mockery::type('array'), Mockery::type('array'), Mockery::type('array'))
            ->andReturnSelf();
        $validatorMock->shouldReceive('fails')->once()->andReturn(true);
        $validatorMock->shouldReceive('errors->first')->once()->andReturn('E-mail must be a valid email address.');

        $controller = new CustomerController();
        $response = $controller->create($request);

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('E-mail must be a valid email address.', session('error'));
    }

    /** @test */
    public function it_updates_a_customer_profile_successfully()
    {
        session()->put('customer', 1);

        $request = Request::create('/customers/update', 'POST', [
            'name' => 'Jane Doe',
            'email' => 'janedoe@example.com',
            'password' => 'newpassword123',
            'bio' => 'Updated bio.',
        ]);

        $customerMock = Mockery::mock('alias:' . Customer::class);
        $customerMock->shouldReceive('find')
            ->once()
            ->with(1)
            ->andReturnSelf();
        $customerMock->shouldReceive('save')
            ->once()
            ->andReturnTrue();

        $validatorMock = Mockery::mock('alias:Illuminate\Support\Facades\Validator');
        $validatorMock->shouldReceive('make')
            ->once()
            ->with(Mockery::type('array'), Mockery::type('array'), Mockery::type('array'), Mockery::type('array'))
            ->andReturnSelf();
        $validatorMock->shouldReceive('fails')->once()->andReturn(false);

        $controller = new CustomerController();
        $response = $controller->update($request);

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('Profile updated successfully', session('success'));
    }

    /** @test */
    public function it_deletes_a_customer_successfully()
    {
        $customerMock = Mockery::mock('alias:' . Customer::class);
        $customerMock->shouldReceive('find')
            ->once()
            ->with(1)
            ->andReturnSelf();
        $customerMock->shouldReceive('delete')
            ->once()
            ->andReturnTrue();
        $customerMock->books = collect([]);

        $controller = new CustomerController();
        $response = $controller->delete(1);

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('User deleted successfully', session('success'));
    }

    /** @test */
    public function it_fails_to_delete_customer_with_books()
    {
        $customerMock = Mockery::mock('alias:' . Customer::class);
        $customerMock->shouldReceive('find')
            ->once()
            ->with(1)
            ->andReturnSelf();
        $customerMock->books = collect([(object)['id' => 1]]);

        $controller = new CustomerController();
        $response = $controller->delete(1);

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('User cannot be deleted because they still have books', session('error'));
    }
}
