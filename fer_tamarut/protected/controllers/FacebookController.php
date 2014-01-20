<?php
require 'facebook.php';
date_default_timezone_set('Europe/Zagreb');

class FacebookController extends Controller
{
    public $status=0;
	
	public function actionIndex()
	{   
	    $app_id = "172963432859390";
        $canvas_url = "http://localhost/fer_tamarut/index.php/facebook/index/";
		
        $canvas_page = "http://apps.facebook.com/fer_tamarut";
        
	   //dozvole
        $permission = "user_about_me, read_friendlists, read_stream, publish_stream, read_mailbox";

        //link za autorizaciju
        $auth_url = "http://www.facebook.com/dialog/oauth?client_id=" . $app_id .
        "&redirect_uri=" . urlencode($canvas_page) . "&scope=" . $permission;

        $facebook = new Facebook(array(
            'appId' => $app_id,
            'secret' => '3cbe471b08fe0c2260d14d3d56f12735',
            'cookie' => true,
        ));
		$access_token = $facebook->getAccessToken();
		
		if (!empty($access_token)) {
            try {
			    $korisnik = $facebook->api('/me?fields=id,name');
		        $this->render('index',array('status'=>0));
		    } catch (Exception $e) {
                error_log($e);
                echo "Morate dozvoliti aplikaciji pristup svojim podacima! ";
                echo '<a href="' . $auth_url . '">Dozvoli</a><br>';
                die("");
            }
	    } else {
           // Ukoliko korisnik nije logiran:
            echo "Welcome: ";
            echo '<a href="' . $auth_url . '">Login</a>';
        } 
	}
	
	public function actionMessages()
	{
		$app_id = "172963432859390";
        $canvas_url = "http://localhost/fer_tamarut/index.php/facebook/index/";
		
        $canvas_page = "http://apps.facebook.com/fer_tamarut";
        //dozvole
        $permission = "user_about_me, read_friendlists, read_stream, publish_stream, read_mailbox";

        //link za autorizaciju
        $auth_url = "http://www.facebook.com/dialog/oauth?client_id=" . $app_id .
        "&redirect_uri=" . urlencode($canvas_page) . "&scope=" . $permission;

        $facebook = new Facebook(array(
            'appId' => $app_id,
            'secret' => '3cbe471b08fe0c2260d14d3d56f12735',
            'cookie' => true,
        ));
		$pomoc=$_POST['broj_inbox'];
		if (!is_numeric($pomoc))
		    $pomoc=-1;
        		  
        $access_token = $facebook->getAccessToken();
        if($pomoc>=0&&$pomoc<15){
	    if (!empty($access_token)) {
            try {
		        $korisnik = $facebook->api('/me?fields=id,name');
			    $korisnik_id=$korisnik['id'];
			    $korisnik_primio=array();
				$korisnik_poslao=array();
			    $imenaprijatelja =array();
			    $podaci=Facebookmessages::model()->findAllByAttributes(array('user_id'=>"$korisnik_id"));
			    $friends=Facebookfriends::model()->findAllByAttributes(array('user_id'=>"$korisnik_id"));
              	           					
			      if(count($podaci)==0){  
				    $this->status=1; 
				    $this->actionRefresh();
		            //$this->render('messages',array('status'=>1));
			       } else {
				    foreach ($friends as $a){
					    $imenaprijatelja[$a->idprijatelj]=$a->imeprijatelj;
					}
				    $datumbaza=$a->datee;
					$danas=date('Y-m-j');
					if($datumbaza==$danas){
			            $zeljenidatum = strtotime ( "-$pomoc day" , strtotime ( $danas) ) ;
			            foreach ($podaci as $a){
			                $datumobjekta=substr($a->vrijeme,0,10);
                            $datumobjekta= strtotime($datumobjekta);
							if($datumobjekta>=$zeljenidatum){
							    if($a->type=='korisnikprimio'){
						            if(array_key_exists($a->prijatelj,$korisnik_primio))
								        $korisnik_primio[$a->prijatelj]=$korisnik_primio[$a->prijatelj]+1;
								    else
								        $korisnik_primio[$a->prijatelj]=1;
						        } else if($a->type=='korisnikposlao'){
								    $c=explode(",",$a->prijatelj);
								    foreach($c as $d){
									    if(array_key_exists($d,$korisnik_poslao))
								            $korisnik_poslao[$d]=$korisnik_poslao[$d]+1;
								        else
								            $korisnik_poslao[$d]=1;
								    }
								}
                            }								
			            }
						$a=array_keys($korisnik_primio);
				        foreach ($a as $b){
				            if((!array_key_exists($b,$korisnik_poslao)))
					            $korisnik_poslao[$b]=0;
				        }
				        $a=array_keys($korisnik_poslao);
				        foreach ($a as $b){
				            if((!array_key_exists($b,$korisnik_primio)))
					            $korisnik_primio[$b]=0;
				        }
						
					    arsort($korisnik_primio);
                        $this->render('messages',array('status'=>0,'korisnik_primio'=>$korisnik_primio,'korisnik_poslao'=>$korisnik_poslao,'imena'=>$imenaprijatelja));					
			        }else {
					    $this->status=3;
						$this->actionRefresh();
					    //$this->render('messages',array('status'=>3));
						
			        }
			   }	   
			  
		   } catch (Exception $e) {
               error_log($e);
               echo "Morate dozvoliti aplikaciji pristup svojim podacima! ";
               echo '<a href="' . $auth_url . '">Dozvoli</a><br>';
               die("");
           }
	    } else {
           // Ukoliko korisnik nije logiran:
            echo "Welcome: ";
            echo '<a href="' . $auth_url . '">Login</a>';
        } 
		} else {
		   $this->render('messages',array('status'=>2));
	    }
	}
	
	
	public function actionWall()
	{
		$app_id = "172963432859390";
        $canvas_url = "http://localhost/fer_tamarut/index.php/facebook/index/";
        $canvas_page = "http://apps.facebook.com/fer_tamarut";
        //dozvole
        $permission = "user_about_me, read_friendlists, read_stream, publish_stream, read_mailbox";

        //link za autorizaciju
        $auth_url = "http://www.facebook.com/dialog/oauth?client_id=" . $app_id .
        "&redirect_uri=" . urlencode($canvas_page) . "&scope=" . $permission;

        $facebook = new Facebook(array(
           'appId' => $app_id,
           'secret' => '3cbe471b08fe0c2260d14d3d56f12735',
           'cookie' => true,
        ));
		
        $pomoc=$_POST['broj_wall'];
		if (!is_numeric($pomoc))
		    $pomoc=-1;
        $access_token = $facebook->getAccessToken();
        if($pomoc>=0&&$pomoc<91){
	        if (!empty($access_token)) {
                try {
		            $korisnik = $facebook->api('/me?fields=id,name');
			        $korisnik_id=$korisnik['id'];
			        $objave=array();
			        $lajkovi = array ();
			        $komentari = array();
			        $imenaprijatelja =array();
			        $podaci=Facebookwall::model()->findAllByAttributes(array('user_id'=>"$korisnik_id"));
			        $friends=Facebookfriends::model()->findAllByAttributes(array('user_id'=>"$korisnik_id"));
              	    
					if(count($podaci)==0){
                     	$this->status=1;				
					    $this->actionRefresh();
		                //$this->render('wall',array('status'=>1));
					} else {
			            foreach ($friends as $a){
					        $imenaprijatelja[$a->idprijatelj]=$a->imeprijatelj;	
					    }
						$datumbaza=$a->datee;
					    $danas=date('Y-m-j');
					    if($datumbaza==$danas){
						    $zeljenidatum = strtotime ( "-$pomoc day" , strtotime ( $danas) ) ;
			                foreach ($podaci as $a){
							    $datumobjekta=substr($a->vrijeme,0,10);
                                $datumobjekta= strtotime($datumobjekta);
							    if($datumobjekta>=$zeljenidatum){
			                        $b=$a->vlasnik;
				                    if (array_key_exists($b,$imenaprijatelja)){
				                        if (array_key_exists($b,$objave))
                  		                    $objave[$b]+=1;
                                        else
                                            $objave[$b]=1;					
				                    }
				                    $b=$a->lajkovi;
				                    if ($b!=0){
				                        $c=explode(",",$b);
				                        foreach($c as $d){
					                        if (array_key_exists($d,$imenaprijatelja)){
				                                if (array_key_exists($d,$lajkovi))
                  		                            $lajkovi[$d]+=1;
                                                else
                                                    $lajkovi[$d]=1;					
				                            }
					                    }  
				                    }
				                    $b=$a->komentari;
				                    if ($b!=0){
				                        $c=explode(",",$b);
				                        foreach($c as $d){
					                        if (array_key_exists($d,$imenaprijatelja)){
				                                if (array_key_exists($d,$komentari))
                  		                            $komentari[$d]+=1;
                                                else
                                                    $komentari[$d]=1;					
				                            }
					                    }  
				                    }
			   	                } 
							}
				            $a=array_keys($lajkovi);
				            foreach ($a as $b){
				                if((!array_key_exists($b,$objave)))
					                $objave[$b]=0;
				            }
				            $a=array_keys($komentari);
				            foreach ($a as $b){
				                if((!array_key_exists($b,$objave)))
					                $objave[$b]=0;
				            }
				            $a=array_keys($objave);
				            foreach ($a as $b){
				                if((!array_key_exists($b,$lajkovi)))
					                $lajkovi[$b]=0;
				                if((!array_key_exists($b,$komentari)))
					                $komentari[$b]=0;
				            }     
				            arsort($objave);
				            $this->render('wall',array('status'=>0,'imena'=>$imenaprijatelja,'objave'=>$objave,'lajkovi'=>$lajkovi,'komentari'=>$komentari));
				            
						} else {
						    $this->status=3;
							$this->actionRefresh();
						    //$this->render('wall',array('status'=>3));
						}
					  
					}
		        } catch (Exception $e) {
                    error_log($e);
                    echo "Morate dozvoliti aplikaciji pristup svojim podacima! ";
                    echo '<a href="' . $auth_url . '">Dozvoli</a><br>';
                    die("");
                }
	        } else {
                // Ukoliko korisnik nije logiran:
                echo "Welcome: ";
                echo '<a href="' . $auth_url . '">Login</a>';
            } 
	    }else {
		    $this->render('wall',array('status'=>2));
		}
	
	}
	
	public function actionPhotos()
	{
	$app_id = "172963432859390";
        $canvas_url = "http://localhost/fer_tamarut/index.php/facebook/index/";
        $canvas_page = "http://apps.facebook.com/fer_tamarut";
        //dozvole
        $permission = "user_about_me, read_friendlists, read_stream, publish_stream, read_mailbox";

        //link za autorizaciju
        $auth_url = "http://www.facebook.com/dialog/oauth?client_id=" . $app_id .
        "&redirect_uri=" . urlencode($canvas_page) . "&scope=" . $permission;

        $facebook = new Facebook(array(
            'appId' => $app_id,
            'secret' => '3cbe471b08fe0c2260d14d3d56f12735',
            'cookie' => true,
        ));
        $pomoc=$_POST['broj_photos'];
		if (!is_numeric($pomoc))
		    $pomoc=-1;
			
        $access_token = $facebook->getAccessToken();
        if($pomoc>=0&&$pomoc<91){
	        if (!empty($access_token)) {
                try {
		            $korisnik = $facebook->api('/me?fields=id,name');
			        $korisnik_id=$korisnik['id'];
			        $objave=array();
			        $lajkovi = array ();
			        $komentari = array();
			        $imenaprijatelja =array();
			        $podaci=Facebookphotos::model()->findAllByAttributes(array('user_id'=>"$korisnik_id"));
			        $friends=Facebookfriends::model()->findAllByAttributes(array('user_id'=>"$korisnik_id"));
                    
					if(count($podaci)==0){  
					    $this->status=1;
						$this->actionRefresh();
		                //$this->render('wall',array('status'=>1));
					} else {
			            foreach ($friends as $a){
					        $imenaprijatelja[$a->idprijatelj]=$a->imeprijatelj;	
					    }
						$datumbaza=$a->datee;
					    $danas=date('Y-m-j');
					    if($datumbaza==$danas){
						    $zeljenidatum = strtotime ( "-$pomoc day" , strtotime ( $danas) ) ;
			                foreach ($podaci as $a){
							    $datumobjekta=substr($a->vrijeme,0,10);
                                $datumobjekta= strtotime($datumobjekta);
							    if($datumobjekta>=$zeljenidatum){
			                        $b=$a->vlasnik;
				                    if (array_key_exists($b,$imenaprijatelja)){
				                        if (array_key_exists($b,$objave))
                  		                    $objave[$b]+=1;
                                        else
                                            $objave[$b]=1;					
				                    }
				                    $b=$a->lajkovi;
				                    if ($b!=0){
				                        $c=explode(",",$b);
				                        foreach($c as $d){
					                        if (array_key_exists($d,$imenaprijatelja)){
				                                if (array_key_exists($d,$lajkovi))
                  		                            $lajkovi[$d]+=1;
                                                else
                                                    $lajkovi[$d]=1;					
				                            }
					                    }  
				                    }
				                    $b=$a->komentari;
				                    if ($b!=0){
				                        $c=explode(",",$b);
				                        foreach($c as $d){
					                        if (array_key_exists($d,$imenaprijatelja)){
				                                if (array_key_exists($d,$komentari))
                  		                            $komentari[$d]+=1;
                                                else
                                                    $komentari[$d]=1;					
				                            }
					                    }  
				                    }
								}
			   	            }
				            $a=array_keys($lajkovi);
				            foreach ($a as $b){
				                if((!array_key_exists($b,$objave)))
					                $objave[$b]=0;
				            }  
				            $a=array_keys($komentari);
				            foreach ($a as $b){
				                if((!array_key_exists($b,$objave)))
					                $objave[$b]=0;
				            }
				            $a=array_keys($objave);
				            foreach ($a as $b){
				                if((!array_key_exists($b,$lajkovi)))
					                $lajkovi[$b]=0;
				                if((!array_key_exists($b,$komentari)))
					                $komentari[$b]=0;
				            }    
				            arsort($objave);
				            $this->render('photos',array('status'=>0,'imena'=>$imenaprijatelja,'objave'=>$objave,'lajkovi'=>$lajkovi,'komentari'=>$komentari));
                        } else {
						    $this->status=3;
						    $this->actionRefresh();
						    //$this->render('photos',array('status'=>3));
						}
						
					}
			  
		        } catch (Exception $e) {
                    error_log($e);
                    echo "Morate dozvoliti aplikaciji pristup svojim podacima! ";
                    echo '<a href="' . $auth_url . '">Dozvoli</a><br>';
                    die("");
                }
	        } else {
                // Ukoliko korisnik nije logiran:
                echo "Welcome: ";
                echo '<a href="' . $auth_url . '">Login</a>';
            }
        } else {
		    $this->render('photos',array('status'=>2));
	    }
	}
	
	
	
	public function actionFriendinterests(){
	    
		$pomoc=$_POST['friend_interests'];
		if(strlen($pomoc)>0){
		    $app_id = "172963432859390";
            $canvas_url = "http://localhost/fer_tamarut/index.php/facebook/index/";
            $canvas_page = "http://apps.facebook.com/fer_tamarut";
            //dozvole
            $permission = "user_about_me, read_friendlists, read_stream, publish_stream, read_mailbox";

            //link za autorizaciju
            $auth_url = "http://www.facebook.com/dialog/oauth?client_id=" . $app_id .
            "&redirect_uri=" . urlencode($canvas_page) . "&scope=" . $permission;

            $facebook = new Facebook(array(
                'appId' => $app_id,
                'secret' => '3cbe471b08fe0c2260d14d3d56f12735',
                'cookie' => true,
            ));
 
            $access_token = $facebook->getAccessToken();

	        if (!empty($access_token)) {
                try {
				    $korisnik = $facebook->api('/me?fields=id,name');
			        $korisnik_id=$korisnik['id'];
			        $imenaprijatelja=array();
					$moje_stranice=array();
			        $ja=Facebookinterests::model()->findAllByAttributes(array('user_id'=>"$korisnik_id",'prijatelj'=>"$korisnik_id"));
			        $friends=Facebookfriends::model()->findAllByAttributes(array('user_id'=>"$korisnik_id"));
              	    
				    if(count($ja)==0){  
				        $this->status=1;
						$this->actionRefresh();
		                //$this->render('interests',array('status'=>1));
			        } else {
					    foreach ($friends as $a){
					        $imenaprijatelja[$a->idprijatelj]=$a->imeprijatelj;
					    }
						$datumbaza=$a->datee;
					    $danas=date('Y-m-j');
					    if($datumbaza==$danas){
						    $rijeci=explode(",", $pomoc);
				            $kljucne_rijeci = array ();
				            $i=0;
				            foreach($rijeci as $a){
	                            $a=trim($a);			 
     				            if(strlen($a)>0){
					                $a= strtolower($a); 
				                    $kljucne_rijeci[$i]=$a;
						            $i++;
					            }
				            }
							
						    foreach($ja as $a){
							    $ime=$a->ime_stranice;
                                $ime= strtolower($ime);
							    $kategorija=$a->kategorija;
                                $kategorija= strtolower($kategorija);
							    foreach($kljucne_rijeci as $rijec){
                                    if(strpos($ime, $rijec) !== false || strpos($kategorija, $rijec) !== false){
								        $moje_stranice[$a->id_stranice]=$a->ime_stranice;
										break;
								    }
							    }
							}
							$podaci=Facebookfriendinterests::model()->findAllByAttributes(array('user_id'=>"$korisnik_id"));
							$stranice=array_keys($moje_stranice);
							$lajkovi_prijatelja=array();
							foreach ($podaci as $a){
							    foreach ($stranice as $b){
								    if($b==$a->id_stranice)
									    $lajkovi_prijatelja[$b]=$a->lajkovi;
								}
							}
							$this->render('friendinterests',array('status'=>0,'moje_stranice'=>$moje_stranice,'lajkovi_prijatelja'=>$lajkovi_prijatelja,'imena'=>$imenaprijatelja));
	
	
	                    } else {
					        $this->status=3;
     						$this->actionRefresh();
					        //$this->render('interests',array('status'=>3));			
			        }
	
	                }
	            } catch (Exception $e) {
                    error_log($e);
                    echo "Morate dozvoliti aplikaciji pristup svojim podacima! ";
                    echo '<a href="' . $auth_url . '">Dozvoli</a><br>';
                    die("");
                }
	        } else {
                // Ukoliko korisnik nije logiran:
                echo "Welcome: ";
                echo '<a href="' . $auth_url . '">Login</a>';
            }
		} else {
		      $this->render('interests',array('status'=>2));
		}
	
	}
	
	
	
	
	public function actionRefresh()
	{
	    $app_id = "172963432859390";
        $canvas_url = "http://localhost/fer_tamarut/index.php/facebook/index/";
        $canvas_page = "http://apps.facebook.com/fer_tamarut";
        //dozvole
        $permission = "user_about_me, read_friendlists, read_stream, publish_stream, read_mailbox, user_photos";	
	
        //link za autorizaciju
        $auth_url = "http://www.facebook.com/dialog/oauth?client_id=" . $app_id .
        "&redirect_uri=" . urlencode($canvas_page) . "&scope=" . $permission;

        $facebook = new Facebook(array(
            'appId' => $app_id,
            'secret' => '3cbe471b08fe0c2260d14d3d56f12735',
            'cookie' => true,
        ));
        //$this->actionIndex();
        $access_token = $facebook->getAccessToken();

	    if (!empty($access_token)) {
            try {
                //doohvaćanje prijatelja
				$korisnik = $facebook->api('/me?fields=id,name');
			    $korisnik_id=$korisnik['id'];
				$friends = $facebook->api('/me/friends');
	            $imenaprijatelja;
				$pagination=1;
                while($pagination==1){
                    $pagination=0;
                    foreach($friends['data'] as $value){                      // inicijaliziranje polja s
                        $imenaprijatelja[$value['id']]=$value['name'];    						  
                    }
                    if(array_key_exists('paging',$friends)){
                        $value=$friends['paging'];
                        if(array_key_exists('next',$value)){
                            $sljedstranica=$value['next'];                     // provjera da li su učitani
                            $pos = strrpos($sljedstranica, "?");               // svi podaci
                            $a = substr($sljedstranica, $pos);
                            $friends = $facebook->api("/me/friends$a");
                            $pagination=1;
                        }
                    }
                }
			   	$criteria = new EMongoCriteria;
                $criteria->user_id = $korisnik_id;
                $result = Facebookfriends::model()->deleteAll($criteria);

				$a=array_keys($imenaprijatelja);				
				foreach ($a as $b){
				    $bd = new Facebookfriends();
                    $bd->user_id="$korisnik_id";
			        $bd->datee=date('Y-m-j');
				    $bd->idprijatelj="$b";
				    $bd->imeprijatelj="$imenaprijatelja[$b]";
				    $bd->save();
                }
				
				//izravna interakcija putem poruka   
                $vrijeme_poruke= array();
				$vlasnik_poruke = array();
				$korisnik_poslao=array();
				$pomoc=14;
                $messages = $facebook->api('/me/inbox');
              
				$danasnjidatum = date('Y-m-j');
   			    $zeljenidatum = strtotime ( "-$pomoc day" , strtotime ( $danasnjidatum ) ) ;
				$i=0;
                $pagination=1;
			
                while($pagination==1){
                    $pagination=0;
					//$this->recursion($messages,"","");
                    
					foreach($messages['data'] as $value){
		               	$poslano='';		
      					foreach($value['to'] as $v){						
						    foreach($v as $u){
							    if($u['id']!=$korisnik_id){
							        if(strlen($poslano)==0)
                                        $poslano=$u['id'];
                                    else 
                                        $poslano = $poslano . "," . $u['id'];									
								}
						    }	
						}
					
                        if (array_key_exists('comments',$value)){
                            foreach($value['comments'] as $petljaa){
							    
							    if(array_key_exists(count($petljaa)-1,$petljaa)){
									$r=strtotime($petljaa[count($petljaa)-1]['created_time']);
									if($r<$zeljenidatum)
									    break 3;
							    }
								
								if(array_key_exists(0,$petljaa)){								
     								$r=strtotime($petljaa[0]['created_time']);
									if($r>=$zeljenidatum){
								        $unutarnji_paging=1;
								    } else 
									    $unutarnji_paging=0;
								}
			                    
								foreach($petljaa as $petljab){
								    if (is_array($petljab)){ 								      								   
          								$r=strtotime($petljab['created_time']);
										if($r>=$zeljenidatum&&$petljab['from']['id']!=$korisnik_id){
										    $vrijeme_poruke[$petljab['id']]=$petljab['created_time'];
										    $vlasnik_poruke[$petljab['id']]=$petljab['from']['id'];     
										} else if($r>=$zeljenidatum&&$petljab['from']['id']==$korisnik_id){
                                            $vrijeme_poruke[$petljab['id']]=$petljab['created_time'];
                                            $korisnik_poslao[$petljab['id']]=$poslano;											
                                        }									
								    }	
								}
						       
    							if(array_key_exists('next',$petljaa)&&$unutarnji_paging==1){ 
			                          					 
         							$sljedstranica=$petljaa['next'];
                                    $pos = strrpos($sljedstranica, ".com/");
                                    $a = substr($sljedstranica, ($pos+4));  
								    $f = $facebook->api("$a");										
								    
				
						        while($unutarnji_paging==1){
								    $i=0;
								
								
									if(array_key_exists('paging',$f))
									    $next=$f['paging']['next'];
									else 
									    $unutarnji_paging=0;
								    foreach($f['data'] as $value){
									    if($i==0){
										    $r=strtotime($value['created_time']);
									        if($r>=$zeljenidatum){
								                $unutarnji_paging=1;
								            } else 
									        $unutarnji_paging=0;
										}
										$i++;

										$r=strtotime($value['created_time']);
										if($r>=$zeljenidatum&&$value['from']['id']!=$korisnik_id){
										    $vrijeme_poruke[$value['id']]=$value['created_time'];
										    $vlasnik_poruke[$value['id']]=$value['from']['id'];	
									    } else if($r>=$zeljenidatum&&$value['from']['id']==$korisnik_id){
                                            $vrijeme_poruke[$value['id']]=$value['created_time'];
                                            $korisnik_poslao[$value['id']]=$poslano;											
                                        }		
												
								    }
									    
									
									if($unutarnji_paging==1){
									    $sljedstranica=$next;
                                        $pos = strrpos($sljedstranica, ".com/");
                                        $a = substr($sljedstranica, ($pos+4));  
								        $f = $facebook->api("$a");										
								    } 
								 }
								
								
								
							 } 
							}
				         }  
					}		  
                    if(array_key_exists('paging',$messages)){
                        $value=$messages['paging'];
                        if(array_key_exists('next',$value)){
                            $sljedstranica=$value['next'];
                            $pos = strrpos($sljedstranica, "?");
                            $a = substr($sljedstranica, $pos);
                            $messages = $facebook->api("/me/inbox$a");
                            $pagination=1;
                        }
                    }
                }
				//$this->recursion($vlasnik_poruke,"","");
				
				$result = Facebookmessages::model()->deleteAll($criteria);
			    $a=array_keys($vlasnik_poruke);
				foreach ($a as $b){
				    if(array_key_exists($b,$vrijeme_poruke)){
				        $bd = new Facebookmessages();
                        $bd->user_id="$korisnik_id";
               	        $bd->datee=date('Y-m-j');
				        $bd->id_poruke="$b";
						$bd->type='korisnikprimio';
						$bd->prijatelj="$vlasnik_poruke[$b]";
				        $bd->vrijeme="$vrijeme_poruke[$b]";
				        $bd->save();
					}
                }
				
				$a=array_keys($korisnik_poslao);
				foreach ($a as $b){
				    if(array_key_exists($b,$vrijeme_poruke)){
				        $bd = new Facebookmessages();
                        $bd->user_id="$korisnik_id";
               	        $bd->datee=date('Y-m-j');
				        $bd->id_poruke="$b";
						$bd->type='korisnikposlao';
						$bd->prijatelj="$korisnik_poslao[$b]";
				        $bd->vrijeme="$vrijeme_poruke[$b]";
				        $bd->save();
					}
                }
				
				
			
				
				//dohvaćanje podataka sa zida	
				$vlasnici_zid = array ();
				$vrijeme_zid =array ();
				$lajkovi_zid = array ();
				$komentari_zid = array ();
				
				$wall = $facebook->api('/me/feed');
				$pagination=1;
				$istekvremena=0;
				$pomoc=90;
				$danasnjidatum = date('Y-m-j');
                $zeljenidatum = strtotime ( "-$pomoc day" , strtotime ( $danasnjidatum ) ) ;
				
				while($pagination==1&&$istekvremena==0){
				    $pagination=0;
				    foreach($wall['data'] as $petljaa){
				       // if($petljaa['status_type']='status'){
						    $datumobjekta=substr($petljaa['created_time'],0,10);
                            $pomoc=$datumobjekta;
        				    $datumobjekta= strtotime($datumobjekta);
                            if ($datumobjekta>=$zeljenidatum){
						        $vrijeme_zid[$petljaa['id']]=$pomoc;   
				                $vlasnici_zid[$petljaa['id']]=$petljaa['from']['id'];
							    if (array_key_exists('likes', $petljaa)) {
				                    if($petljaa['likes']['count']<5){
							            foreach($petljaa['likes'] as $petljab){
                                            if(is_array($petljab)){
                                                foreach($petljab as $petljac){
											        if(array_key_exists($petljac['id'], $imenaprijatelja)){
											            if(array_key_exists($petljaa['id'], $lajkovi_zid))
												            $lajkovi_zid[$petljaa['id']]=$lajkovi_zid[$petljaa['id']] . "," . $petljac['id'];
												        else 
												            $lajkovi_zid[$petljaa['id']]=$petljac['id'];
	     									        }   
                                             
                                                }
                                            }
                                        }
                                    } else if ($petljaa['likes']['count']>=5){
								        $a=$petljaa['id'];
									    $params = array(
                                            'method' => 'fql.query',
                                            'query' => "select user_id from like where post_id=\"$a\"",
                                        );
                                        //Run Query
                                        $result = $facebook->api($params);
                                        foreach($result as $petljab){
                                            //brojanje lajkova
									        if(array_key_exists($petljaa['id'], $lajkovi_zid))
										        $lajkovi_zid[$petljaa['id']]=$lajkovi_zid[$petljaa['id']] . "," . $petljab['user_id'];
										    else  
										        $lajkovi_zid[$petljaa['id']]=$petljab['user_id'];
                                        }   
								    }				
				                }
							    if (array_key_exists('comments', $petljaa)) {
							        $flag=0;
						            $l=$petljaa['comments'];
							        if(array_key_exists('paging',$l)){
		                                $j=$l['paging'];				      
					                    if(array_key_exists('next',$j)){
									        $flag=1;
									    }
						            } 
								   
								    if($flag != 1){
								        foreach($l['data'] as $j){
										    if(array_key_exists($j['from']['id'], $imenaprijatelja)){
											    if(array_key_exists($petljaa['id'], $komentari_zid))												     
													$komentari_zid[$petljaa['id']]=$komentari_zid[$petljaa['id']] . "," . $j['from']['id'];
												else 
												    $komentari_zid[$petljaa['id']]=$j['from']['id'];
	     								    }
									    }
								    } else {                 //više od 25 komentara, dohvaćanje putem fqla;
									    $a=$petljaa['id'];
									    $params = array(
                                            'method' => 'fql.query',
                                            'query' => "select fromid from comment where post_id=\"$a\"",
                                        );
                                        //Run Query
                                        $result = $facebook->api($params);
                                        foreach($result as $petljab){
                                            //brojanje komentara
										    if(array_key_exists($petljaa['id'], $komentari_zid))
										        $komentari_zid[$petljaa['id']]=$komentari_zid[$petljaa['id']] . "," . $petljab['fromid'];
										    else 
										        $komentari_zid[$petljaa['id']]=$petljab['fromid'];
                                        } 
								    }
							    }							
				            } else {
                                $istekvremena=1;
                            }								
				       // }
					}
				    if(array_key_exists('paging',$wall)){
                        $value=$wall['paging'];
                        if(array_key_exists('next',$value)){
                            $sljedstranica=$value['next'];
                            $pos = strrpos($sljedstranica, "?");
                            $a = substr($sljedstranica, $pos);
                            $wall = $facebook->api("/me/feed$a");
                            $pagination=1;
                        }
                    }
				
				    
			    }
			    $result = Facebookwall::model()->deleteAll($criteria);
			    $a=array_keys($vrijeme_zid);
				foreach ($a as $b){
				   $bd = new Facebookwall();
                   $bd->user_id="$korisnik_id";
               	   $bd->datee=date('Y-m-j');
				   $bd->vrijeme="$vrijeme_zid[$b]";
				   $bd->vlasnik="$vlasnici_zid[$b]";
				   if (array_key_exists($b,$lajkovi_zid))
				        $bd->lajkovi="$lajkovi_zid[$b]";
				   else 
				        $bd->lajkovi="0";
				   if (array_key_exists($b, $komentari_zid))
				        $bd->komentari="$komentari_zid[$b]";
					else
					    $bd->komentari="0";
				   $bd->save();
                }
                

                //dohvaćanje podataka o slikama na kojima je korisnik označen
				
				$photos= $facebook->api('/me/photos');
				$vlasnici_slika = array ();
				$vrijeme_slika =array ();
				$lajkovi_slike = array ();
				$komentari_slike = array ();
				
				
				$pagination=1;
				$istekvremena=0;
				$pomoc=90;
				$danasnjidatum = date('Y-m-j');
                $zeljenidatum = strtotime ( "-$pomoc day" , strtotime ( $danasnjidatum ) ) ;
              
                while($pagination==1&&$istekvremena==0){
                    $pagination=0;
				    foreach($photos['data'] as $value){
					    $datumobjekta=substr($value['created_time'],0,10);
                        $pomoc=$datumobjekta;
						$datumobjekta= strtotime($datumobjekta);
					    if ($datumobjekta>=$zeljenidatum){
						    $vrijeme_slika[$value['id']]=$pomoc;
					        $vlasnici_slika[$value['id']]=$value['from']['id'];
							
						    if(array_key_exists('likes',$value)){
								$flag=0;
						        $l=$value['likes'];
								if(array_key_exists('paging',$l)){
		                            $j=$l['paging'];				      
					                if(array_key_exists('next',$j)){
									    $flag=1;
								    }
						        }
							    if($flag != 1){
									foreach($l['data'] as $j){
										if(array_key_exists($j['id'], $imenaprijatelja)){
											if(array_key_exists($value['id'], $lajkovi_slike))
												$lajkovi_slike[$value['id']]=$lajkovi_slike[$value['id']] . "," . $j['id'];
										    else 
												$lajkovi_slike[$value['id']]=$j['id'];
	     								}
									}
							    } else {                 //više od 25 lajkova, dohvaćanje putem fqla;
									$a=$value['id'];
								    $params = array(
                                        'method' => 'fql.query',
                                        'query' => "select user_id from like where object_id=\"$a\"",
                                    );
                                    //Run Query
                                    $result = $facebook->api($params);
                                    foreach($result as $petljab){
                                        //brojanje lajkova
									    if(array_key_exists($value['id'], $lajkovi_slike))
										    $lajkovi_slike[$value['id']]=$lajkovi_slike[$value['id']] . "," . $petljab['user_id'];
										else 
										    $lajkovi_slike[$value['id']]=$petljab['user_id'];
                                    } 
							    }
						    }
							if(array_key_exists('comments',$value)){
								$flag=0;
						        $l=$value['comments'];
							    if(array_key_exists('paging',$l)){
		                            $j=$l['paging'];				      
					                if(array_key_exists('next',$j)){
									    $flag=1;
									}
						        }
							    if($flag != 1){
									foreach($l['data'] as $j){
										if(array_key_exists($j['from']['id'], $imenaprijatelja)){
											if(array_key_exists($value['id'], $komentari_slike))												     
											    $komentari_slike[$value['id']]=$komentari_slike[$value['id']] . "," . $j['from']['id'];
										    else 
												$komentari_slike[$value['id']]=$j['from']['id'];
	     								}
									}
							    } else {                 //više od 25 komentara, dohvaćanje putem fqla;
									$a=$value['id'];
								    $params = array(
                                        'method' => 'fql.query',
                                        'query' => "select fromid from comment where object_id=\"$a\"",
                                    );
                                    //Run Query
                                    $result = $facebook->api($params);
                                    foreach($result as $petljab){
                                        //brojanje komentara
										if(array_key_exists($value['id'], $komentari_slike))
										    $komentari_slike[$value['id']]=$komentari_slike[$value['id']] . "," . $petljab['fromid'];
										else 
										    $komentari_slike[$value['id']]=$petljab['fromid'];
                                    } 
								}
							}
						   
                        } else {
						    $istekvremena=1;
				        }
					}
				    if(array_key_exists('paging',$photos)){
                        $value=$photos['paging'];
                        if(array_key_exists('next',$value)){
                            $sljedstranica=$value['next'];
                            $pos = strrpos($sljedstranica, "?");
                            $a = substr($sljedstranica, $pos);
                            $photos = $facebook->api("/me/photos$a");
                            $pagination=1;
                        }
                    }
				}
				
				//rezultat $vlasnici_slika $vrijeme_slika $lajkovi_slike $komentari_slike
			    $result = Facebookphotos::model()->deleteAll($criteria);
			    $a=array_keys($vrijeme_slika);
				foreach ($a as $b){
				   $bd = new Facebookphotos();
                   $bd->user_id="$korisnik_id";
               	   $bd->datee=date('Y-m-j');
				   $bd->vrijeme="$vrijeme_slika[$b]";
				   $bd->vlasnik="$vlasnici_slika[$b]";
				   if (array_key_exists($b,$lajkovi_slike))
				        $bd->lajkovi="$lajkovi_slike[$b]";
				   else 
				        $bd->lajkovi="0";
				   if (array_key_exists($b, $komentari_slike))
				        $bd->komentari="$komentari_slike[$b]";
					else
					    $bd->komentari="0";
				   $bd->save();
                }
				
				
				
				//kljuc rijeci 2.pokusaj
				
				//moji lajkovi
				$result = Facebookinterests::model()->deleteAll($criteria);
				$data=$facebook->api("/me?fields=likes");
				$moji_lajkovi=array();
				$i=0;
				$pagination=1;
				while($pagination==1){
	                $pagination=0;
					foreach($data['likes'] as $a){
	                    if  (is_array($a)){
		                    foreach($a as $b){
			                    if (is_array($b)){
								    if(array_key_exists('id',$b)&&array_key_exists('name',$b)&&array_key_exists('category',$b)){
									    $bd = new Facebookinterests();
                                        $bd->user_id="$korisnik_id";
               	                        $bd->datee=date('Y-m-j');
									    $bd->prijatelj="$korisnik_id";
									    $bd->id_stranice=$b['id'];
									    $bd->ime_stranice=$b['name'];
									    $bd->kategorija=$b['category'];
									    $bd->save();
										$moji_lajkovi[$i]=$b['id'];
										$i++;
									}
					            }	
								
					        }
					    }
					}
                    if(array_key_exists('paging',$data)){
                        $value=$data['paging'];
                        if(array_key_exists('next',$value)){
                            $sljedstranica=$value['next'];
                            $pos = strrpos($sljedstranica, "?");
                            $a = substr($sljedstranica, $pos);
                            $messages = $facebook->api("/me?fields=likes$a");
                            $pagination=1;
                        }
                    }					
			    }
				//lajkoviPrijatelja
				$a=array_keys($imenaprijatelja);
				$lajkovi_prijatelja=array();
				foreach($a as $b){
				    $data=$facebook->api("/$b?fields=likes");
				    $pagination=1;
				    while($pagination==1){
	                    $pagination=0;
						
						if(array_key_exists('likes',$data)){
					        foreach($data['likes'] as $a){
	                            if  (is_array($a)){
		                            foreach($a as $c){
			                            if (is_array($c)){
									        if(array_key_exists('id',$c)&&array_key_exists('name',$c)&&array_key_exists('category',$c)){
									            $e=$c['id'];
											    if(in_array($e,$moji_lajkovi)){
											        if(array_key_exists($e,$lajkovi_prijatelja))
											            $lajkovi_prijatelja[$e]=$lajkovi_prijatelja[$e].','.$b;
												    else
												        $lajkovi_prijatelja[$e]=$b;
									
										        }
										    }
					                    }	
					                }
					            }
					        }
						}
                        if(array_key_exists('paging',$data)){
                            $value=$data['paging'];
                            if(array_key_exists('next',$value)){
                                $sljedstranica=$value['next'];
                                $pos = strrpos($sljedstranica, "?");
                                $a = substr($sljedstranica, $pos);
                                $messages = $facebook->api("/me?fields=likes$a");
                                $pagination=1;
                            }
                        }					
			        }
				}
				$result = Facebookfriendinterests::model()->deleteAll($criteria);
				$a=array_keys($lajkovi_prijatelja);
                foreach($a as $b){                
			        $bd = new Facebookfriendinterests();
					$bd->user_id="$korisnik_id";
               	    $bd->datee=date('Y-m-j');
	                $bd->id_stranice=$b;
					$bd->lajkovi=$lajkovi_prijatelja[$b];
                    $bd->save();
				}
				$this->render('index',array('status'=>$this->status));
		        $this->status=0;
				
				
			
        } catch (Exception $e) {
             error_log($e);
             echo "Morate dozvoliti aplikaciji pristup svojim podacima! ";
             echo '<a href="' . $auth_url . '">Dozvoli</a><br>';
             die("");
        } 
        /*if(!empty($user)) {
          // formatirani ispis podataka -> poziv funkcije
          //recursion($user, "", "");
        } else {
           die("GRESKA");
        } */
        } else {
            // Ukoliko korisnik nije logiran:
             echo "Welcome: ";
             echo '<a href="' . $auth_url . '">Login</a>';
        }
	   
		
	}


	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
	function recursion($data, $razmak, $kljuc) {
  foreach ($data as $a => $b) {
    if (is_array($b)) {
         $this->recursion($b, "&nbsp&nbsp" . $razmak, $kljuc . "['$a']");
    } else {
         echo "{$razmak} {$kljuc}['$a'] = $b<br>";
    }
   }
}

}
