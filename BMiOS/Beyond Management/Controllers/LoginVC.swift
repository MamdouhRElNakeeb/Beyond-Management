//
//  ViewController.swift
//  Beyond Management
//
//  Created by Mamdouh El Nakeeb on 12/2/17.
//  Copyright © 2017 Beyond Management. All rights reserved.
//

import UIKit

class LoginVC: UIViewController {

    @IBOutlet weak var fbLoginBtn: UIButton!
    @IBOutlet weak var loginBtn: UIButton!
    
    override func viewDidLoad() {
        super.viewDidLoad()
        
        // Do any additional setup after loading the view.
        initView()
    }
    
    func initView(){
        
        loginBtn.layer.cornerRadius = loginBtn.frame.height / 2
        fbLoginBtn.layer.cornerRadius = fbLoginBtn.frame.height / 2
    }
    
    @IBAction func loginBtnClick(_ sender: UIButton) {
        
        let homeNC = self.storyboard?.instantiateViewController(withIdentifier: "homeNC") as? UINavigationController
        self.present(homeNC!, animated: true, completion: nil)
        
    }
    
    @IBAction func linkToRegClick(_ sender: UIButton) {
        
        let registerVC = self.storyboard?.instantiateViewController(withIdentifier: "registerVC") as? RegisterVC
        self.present(registerVC!, animated: true, completion: nil)
    }
    
}

