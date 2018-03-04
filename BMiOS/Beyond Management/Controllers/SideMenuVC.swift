//
//  SideMenuVC.swift
//  Beyond Management
//
//  Created by Mamdouh El Nakeeb on 12/20/17.
//  Copyright © 2017 Beyond Management. All rights reserved.
//

import UIKit

class SideMenuVC: UIViewController {

    var menuTV = UITableView()
    var bmgIV = UIImageView()
    var bmgLbl = UILabel()
    
    override func viewDidLoad() {
        super.viewDidLoad()

        // Do any additional setup after loading the view.
        
        menuTV.dataSource = self
        menuTV.delegate = self
        menuTV.frame = CGRect(x: 20, y: view.frame.height / 2 - 50 * 5 / 2, width: view.frame.width * 2 / 3 - 60, height: 50 * 5)
        menuTV.backgroundColor = UIColor.clear
        
        bmgIV.frame = CGRect(x: menuTV.frame.minX, y: menuTV.frame.minY - 100, width: menuTV.frame.width, height: 60)
        bmgIV.image = UIImage(named: "logo")
        bmgIV.contentMode = .scaleAspectFit
        
        bmgLbl.frame = CGRect(x: menuTV.frame.minX, y: bmgIV.frame.maxY - 15, width: menuTV.frame.width, height: 60)
        bmgLbl.text = "Immigration Portal"
        bmgLbl.font = UIFont.boldSystemFont(ofSize: 20)
        bmgLbl.textAlignment = .center
        
        view.addSubview(menuTV)
        view.addSubview(bmgIV)
        view.addSubview(bmgLbl)
    }
}

extension SideMenuVC: UITableViewDelegate, UITableViewDataSource {
    
    func tableView(_ tableView: UITableView, cellForRowAt indexPath: IndexPath) -> UITableViewCell {
        
        switch indexPath.row {
        case 0:
            let cell = UITableViewCell()
            cell.textLabel?.text = "Available VISA"
            cell.backgroundColor = UIColor.clear
            return cell
        case 1:
            let cell = UITableViewCell()
            cell.textLabel?.text = "My Applications"
            let badge = UILabel()
            badge.frame = CGRect(x: menuTV.frame.width - 50, y: 10, width: 30, height: 30)
            badge.backgroundColor = UIColor.red
            badge.layer.cornerRadius = 15
            badge.layer.masksToBounds = true
            badge.text = "1"
            badge.textColor = UIColor.white
            badge.textAlignment = .center
//            cell.contentView.addSubview(badge)
            cell.backgroundColor = UIColor.clear
            return cell
        case 2:
            let cell = UITableViewCell()
            cell.textLabel?.text = "About us"
            cell.backgroundColor = UIColor.clear
            return cell
        case 3:
            let cell = UITableViewCell()
            cell.textLabel?.text = "Contact"
            cell.backgroundColor = UIColor.clear
            return cell
        default:
            let cell = UITableViewCell()
            cell.textLabel?.text = "Logout"
            cell.backgroundColor = UIColor.clear
            return cell
        }
    }
    
    
    func tableView(_ tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        return 5
    }
    
    func tableView(_ tableView: UITableView, didSelectRowAt indexPath: IndexPath) {
        
        tableView.deselectRow(at: indexPath, animated: true)
        
        switch indexPath.row {
        case 0:
            let vc = self.storyboard?.instantiateViewController(withIdentifier: "homeNC") as? UINavigationController
//            self.navigationController?.pushViewController(vc!, animated: true)
            sideMenuController?.embed(centerViewController: vc!)
            break
            
        case 1:
            let vc = self.storyboard?.instantiateViewController(withIdentifier: "ApplicationsNC") as? UINavigationController
//            self.navigationController?.pushViewController(vc!, animated: true)
            
            sideMenuController?.embed(centerViewController: vc!)
            break
            
        case 2:
            let vc = self.storyboard?.instantiateViewController(withIdentifier: "AboutNC") as? UINavigationController
//            self.navigationController?.pushViewController(vc!, animated: true)
            sideMenuController?.embed(centerViewController: vc!)
            break
            
        case 3:
            let vc = self.storyboard?.instantiateViewController(withIdentifier: "ContactNC") as? UINavigationController
//            self.navigationController?.pushViewController(vc!, animated: true)
            sideMenuController?.embed(centerViewController: vc!)
            break
        
        default:
            UserDefaults.standard.set(false, forKey: "login")
            let vc = self.storyboard?.instantiateViewController(withIdentifier: "loginVC") as? LoginVC
//            self.present(vc!, animated: true, completion: nil)
            sideMenuController?.embed(centerViewController: vc!)
            break
        }
    }
}
