<?php

use Authority\Repo\User\UserInterface;
use Authority\Repo\Group\GroupInterface;
use Authority\Service\Form\Register\RegisterForm;
use Authority\Service\Form\User\UserForm;
use Authority\Service\Form\ResendActivation\ResendActivationForm;
use Authority\Service\Form\ForgotPassword\ForgotPasswordForm;
use Authority\Service\Form\ChangePassword\ChangePasswordForm;
use Authority\Service\Form\SuspendUser\SuspendUserForm;

class UserController extends BaseController {

	protected $user;
	protected $group;
	protected $registerForm;
	protected $userForm;
	protected $resendActivationForm;
	protected $forgotPasswordForm;
	protected $changePasswordForm;
	protected $suspendUserForm;

	/**
	 * Instantiate a new UserController
	 */
	public function __construct(
		UserInterface $user, 
		GroupInterface $group, 
		RegisterForm $registerForm, 
		UserForm $userForm,
		ResendActivationForm $resendActivationForm,
		ForgotPasswordForm $forgotPasswordForm,
		ChangePasswordForm $changePasswordForm,
		SuspendUserForm $suspendUserForm)
	{
		$this->user = $user;
		$this->group = $group;
		$this->registerForm = $registerForm;
		$this->userForm = $userForm;
		$this->resendActivationForm = $resendActivationForm;
		$this->forgotPasswordForm = $forgotPasswordForm;
		$this->changePasswordForm = $changePasswordForm;
		$this->suspendUserForm = $suspendUserForm;

		//Check CSRF token on POST
		$this->beforeFilter('csrf', array('on' => 'post'));

		// Set up Auth Filters
		$this->beforeFilter('auth', array('only' => array('change')));
		$this->beforeFilter('inGroup:Admins', array('only' => array('show', 'index', 'destroy', 'suspend', 'unsuspend', 'ban', 'unban', 'edit', 'update')));
		//array('except' => array('create', 'store', 'activate', 'resend', 'forgot', 'reset')));
	}


	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $users = $this->user->all();
      
        return View::make('users.index')->with('users', $users);
	}

	/**
	 * Show the form for creating a new user.
	 *
	 * @return Response
	 */
	public function create()
	{
        return View::make('users.create');
	}

	/**
	 * Store a newly created user.
	 *
	 * @return Response
	 */
	public function store()
	{
		// Form Processing
        $result = $this->registerForm->save( Input::all() );

        if( $result['success'] )
        {
            Event::fire('user.signup', array(
            	'email' => $result['mailData']['email'], 
            	'userId' => $result['mailData']['userId'], 
                'activationCode' => $result['mailData']['activationCode']
            ));

            // Success!
            Session::flash('success', $result['message']);
            return Redirect::route('home');

        } else {
            Session::flash('error', $result['message']);
            return Redirect::action('UserController@create')
                ->withInput()
                ->withErrors( $this->registerForm->errors() );
        }
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
        $user = $this->user->byId($id);

        if($user == null || !is_numeric($id))
        {
            // @codeCoverageIgnoreStart
            return \App::abort(404);
            // @codeCoverageIgnoreEnd
        }

        return View::make('users.show')->with('user', $user);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
        $user = $this->user->byId($id);

        if($user == null || !is_numeric($id))
        {
            // @codeCoverageIgnoreStart
            return \App::abort(404);
            // @codeCoverageIgnoreEnd
        }

        $currentGroups = $user->getGroups()->toArray();
        $userGroups = array();
        foreach ($currentGroups as $group) {
        	array_push($userGroups, $group['name']);
        }
        $allGroups = $this->group->all();

        return View::make('users.edit')->with('user', $user)->with('userGroups', $userGroups)->with('allGroups', $allGroups);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
        if(!is_numeric($id))
        {
            // @codeCoverageIgnoreStart
            return \App::abort(404);
            // @codeCoverageIgnoreEnd
        }

		// Form Processing
        $result = $this->userForm->update( Input::all() );

        if( $result['success'] )
        {
            // Success!
            Session::flash('success', $result['message']);
            return Redirect::action('UserController@show', array($id));

        } else {
            Session::flash('error', $result['message']);
            return Redirect::action('UserController@edit', array($id))
                ->withInput()
                ->withErrors( $this->userForm->errors() );
        }
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        if(!is_numeric($id))
        {
            // @codeCoverageIgnoreStart
            return \App::abort(404);
            // @codeCoverageIgnoreEnd
        }

		if ($this->user->destroy($id))
		{
			Session::flash('success', 'User Deleted');
            return Redirect::to('/users');
        }
        else 
        {
        	Session::flash('error', 'Unable to Delete User');
            return Redirect::to('/users');
        }
	}

	/**
	 * Activate a new user
	 * @param  int $id   
	 * @param  string $code 
	 * @return Response
	 */
	public function activate($id, $code)
	{
        if(!is_numeric($id))
        {
            // @codeCoverageIgnoreStart
            return \App::abort(404);
            // @codeCoverageIgnoreEnd
        }

		$result = $this->user->activate($id, $code);

        if( $result['success'] )
        {
            // Success!
            Session::flash('success', $result['message']);
            return Redirect::route('home');

        } else {
            Session::flash('error', $result['message']);
            return Redirect::route('home');
        }
	}

	/**
	 * Process resend activation request
	 * @return Response
	 */
	public function resend()
	{
		// Form Processing
        $result = $this->resendActivationForm->resend( Input::all() );

        if( $result['success'] )
        {
            Event::fire('user.resend', array(
				'email' => $result['mailData']['email'], 
				'userId' => $result['mailData']['userId'], 
				'activationCode' => $result['mailData']['activationCode']
			));

            // Success!
            Session::flash('success', $result['message']);
            return Redirect::route('home');
        } 
        else 
        {
            Session::flash('error', $result['message']);
            return Redirect::route('profile')
                ->withInput()
                ->withErrors( $this->resendActivationForm->errors() );
        }
	}

	/**
	 * Process Forgot Password request
	 * @return Response
	 */
	public function forgot()
	{
		// Form Processing
        $result = $this->forgotPasswordForm->forgot( Input::all() );

        if( $result['success'] )
        {
            Event::fire('user.forgot', array(
				'email' => $result['mailData']['email'],
				'userId' => $result['mailData']['userId'],
				'resetCode' => $result['mailData']['resetCode']
			));

            // Success!
            Session::flash('success', $result['message']);
            return Redirect::route('home');
        } 
        else 
        {
            Session::flash('error', $result['message']);
            return Redirect::route('forgotPasswordForm')
                ->withInput()
                ->withErrors( $this->forgotPasswordForm->errors() );
        }
	}

	/**
	 * Process a password reset request link
	 * @param  [type] $id   [description]
	 * @param  [type] $code [description]
	 * @return [type]       [description]
	 */
	public function reset($id, $code)
	{
        if(!is_numeric($id))
        {
            // @codeCoverageIgnoreStart
            return \App::abort(404);
            // @codeCoverageIgnoreEnd
        }

		$result = $this->user->resetPassword($id, $code);

        if( $result['success'] )
        {
            Event::fire('user.newpassword', array(
				'email' => $result['mailData']['email'],
				'newPassword' => $result['mailData']['newPassword']
			));

            // Success!
            Session::flash('success', $result['message']);
            return Redirect::route('home');

        } else {
            Session::flash('error', $result['message']);
            return Redirect::route('home');
        }
	}

	/**
	 * Process a password change request
	 * @param  int $id 
	 * @return redirect     
	 */
	public function change($id)
	{
        if(!is_numeric($id))
        {
            // @codeCoverageIgnoreStart
            return \App::abort(404);
            // @codeCoverageIgnoreEnd
        }

		$data = Input::all();
		$data['id'] = $id;

		// Form Processing
        $result = $this->changePasswordForm->change( $data );

        if( $result['success'] )
        {
            // Success!
            Session::flash('success', $result['message']);
            return Redirect::route('home');
        } 
        else 
        {
            Session::flash('error', $result['message']);
            return Redirect::action('UserController@edit', array($id))
                ->withInput()
                ->withErrors( $this->changePasswordForm->errors() );
        }
	}

	/**
	 * Process a suspend user request
	 * @param  int $id 
	 * @return Redirect     
	 */
	public function suspend($id)
	{
        if(!is_numeric($id))
        {
            // @codeCoverageIgnoreStart
            return \App::abort(404);
            // @codeCoverageIgnoreEnd
        }

		// Form Processing
        $result = $this->suspendUserForm->suspend( Input::all() );

        if( $result['success'] )
        {
            // Success!
            Session::flash('success', $result['message']);
            return Redirect::to('users');

        } else {
            Session::flash('error', $result['message']);
            return Redirect::action('UserController@suspend', array($id))
                ->withInput()
                ->withErrors( $this->suspendUserForm->errors() );
        }
	}

	/**
	 * Unsuspend user
	 * @param  int $id 
	 * @return Redirect     
	 */
	public function unsuspend($id)
	{
        if(!is_numeric($id))
        {
            // @codeCoverageIgnoreStart
            return \App::abort(404);
            // @codeCoverageIgnoreEnd
        }

		$result = $this->user->unSuspend($id);

        if( $result['success'] )
        {
            // Success!
            Session::flash('success', $result['message']);
            return Redirect::to('users');

        } else {
            Session::flash('error', $result['message']);
            return Redirect::to('users');
        }
	}

	/**
	 * Ban a user
	 * @param  int $id 
	 * @return Redirect     
	 */
	public function ban($id)
	{
        if(!is_numeric($id))
        {
            // @codeCoverageIgnoreStart
            return \App::abort(404);
            // @codeCoverageIgnoreEnd
        }

		$result = $this->user->ban($id);

        if( $result['success'] )
        {
            // Success!
            Session::flash('success', $result['message']);
            return Redirect::to('users');

        } else {
            Session::flash('error', $result['message']);
            return Redirect::to('users');
        }
	}

	public function unban($id)
	{
        if(!is_numeric($id))
        {
            // @codeCoverageIgnoreStart
            return \App::abort(404);
            // @codeCoverageIgnoreEnd
        }
        
		$result = $this->user->unBan($id);

        if( $result['success'] )
        {
            // Success!
            Session::flash('success', $result['message']);
            return Redirect::to('users');

        } else {
            Session::flash('error', $result['message']);
            return Redirect::to('users');
        }
	}
	
	/*
	 * changeprofile
	 * 
	 * @purpose : start de Form changeprofile waarin je alle gegevens van deze person (User, UserExtra, Group ....) kan aanpassen
	 * @remark : je komt hier vanuit het startscherm widget verander je (eigen) profiel. Of als secretaris om het profiel van iemand aan te passen
	 * @args : id --> de id van de user die aangepast moet worden
	 * @return : success
	 * 
	 */
	public function changeprofile($id)
	{
		$user = User::find($id);
		if ($user == null || !is_numeric($id))
		{
			return \App::abort(404);
		}
		
		$currentGroups = $user->getGroups()->toArray();
		$userGroups = array();
		foreach ($currentGroups as $group) {
			array_push($userGroups, $group['name']);
		}
		$allGroups = $this->group->all();
		
		return View::make('users.changeprofile', array('id' => $id))->with('user', $user)->with('userGroups', $userGroups)->with('allGroups', $allGroups);
	}
	
	/*
	 * storeprofile
	 * 
	 * @purpose : de data verzameld in het formulier 'verander je profiel' wordt hier gevalideerd en als ok gespaard in de 
	 *   diverse tabellen : users, userextras
	 * 
	 * @args : Input
	 * @return : success or errors
	 */
	public function storeprofile()
	{
		$data = Input::all();
		$validator = Validator::make($data, User::$rules);
		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}
		$result = $this->registerForm->save($data);
		
		// userExtra bekijken
		$extradata = array(
			'street' => $data['street'],
			'housenr' => $data['housenr'],
			'zip' => $data['zip'],
			'city' => $data['city'],
			'country' => $data['country'],
			'birthdate' => $data['birthdate'],
			'phone' => $data['phone'],
			'gsm' => $data['gsm'],
		);
		
		// bekijk hier manueel - omdat het met de validator niet werkt - de phone en de gsm
		$phone = $data['phone'];
		$gsm = $data['gsm'];
		
		$phoneStatus = ($phone == null || strlen(trim($phone) == 0) ? null : $phone);
		$gsmStatus = ($gsm == null || strlen(trim($gsm) == 0) ? null : $gsm);
		
		if (!isset($phoneStatus))
		{
			$rules = array('gsm' => 'required|phonerule');
			$input = array('gsm' => $gsm);
			$message = array('gsm.phonerule' => 'Het gsm nummer is niet in het juiste formaat','gsm.required' => 'telefoon of GSM vereist');
			$v = Validator::make($input,$rules, $message);
			if ($v->fails())
			{
				return Redirect::back()->withErrors($v)->withInput();
			}
		}
		
		if (!isset($gsmStatus))
		{
			$rules = array('phone' => 'required|phonerule');
			$input = array('phone' => $phone);
			$message = array('phone.phonerule' => 'Het telefoonnummer is niet in het juiste formaat', 'phone.required' => 'telefoon of GSM vereist');
			$v = Validator::make($input,$rules, $message);
			if ($v->fails())
			{
				return Redirect::back()->withErrors($v)->withInput();
			}			
		}
		
		// Na de telefoon en GSM zijn er geen testen meer uit te voeren
		// Dus nu moeten we alles opsparen
		$user = User::findOrFail($data['id']);
		$userdata = array(
			'id' => $data['id'],
			'email' => $data['email'],
			'first_name' => $data['first_name'],
			'last_name' => $data['last_name'],
		);
		$user->update($userdata);
		
		$userExtra = UserExtra::where('user_id', $data['id']);
		if ($userExtra)
		{
			$userExtra->update($extradata);
		}
		
		// Nu moeten we nog de groep bekijken (als die gewijzigd is)
		var_dump($data);
		

		print("<br />thisUser");
		
		$allGroups = $this->group->all();
		$thisUser = Sentry::findUserById($data['id']);
		
		// haal alle entries in tabel users_groups met user_id gelijk aan deze id
		$groepenobj = DB::select("select group_id FROM users_groups WHERE user_id = {$data['id']}");
		foreach ($groepenobj AS $item)
		{
			$groepen[] = $item->group_id;
		}
		if (!$groepen)
		{
			// nu moet je er eventueel maken
			die("waarschijnlijk moet je groepen aanmaken");
		} else {
			// groepen = de groepen in de tabel voor deze gebruiker
			// allGroups = de opsomming van alle mogelijke groepen
			foreach($allGroups AS $group)
			{
				try{
					// we doorlopen alle mogelijke groepen -
					//  als we een groep vinden in het record van de gebruiker
					if (in_array($group->id, $groepen))
					{
					//      als die groep ook aangevinkt is in het formulier
					//         dan : doe niets, want alles blijft zoals het was
					//         anders : verwijder de groep uit de databank
					   if (!isset($data["{$group->name}"]))
					   {
					   		$gr = Sentry::findGroupById($group->id);
					   		$thisUser->removeGroup($gr);
	//				        print("<br /> de groep {$group->name} verwijderen we uit het record in de db");
					   }
					} else {
					//  anders
					//      als die groep  aangevinkt is in het formulier				
						if (isset($data["{$group->name}"]))
						{
					//         dan : voeg die groep toe bij de gebruiker
							$gr = Sentry::findGroupById($group->id);
							$thisUser->addGroup($gr);
						} 
					//         anders : laat zoals het is
					}
				} catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
				{
				    echo 'User was not found.';
				}
				catch (Cartalyst\Sentry\Groups\GroupNotFoundException $e)
				{
				    echo 'Group was not found.';
				}				
			}
		}		
		return Redirect::to('inhoud');

	}
}

	
