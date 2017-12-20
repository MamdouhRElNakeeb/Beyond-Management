//
//  VisaServicesTVCell.swift
//  Beyond Management
//
//  Created by Mamdouh El Nakeeb on 12/5/17.
//  Copyright © 2017 Beyond Management. All rights reserved.
//

import UIKit
import SDWebImage

class VisaServicesTVCell: UITableViewCell {

    @IBOutlet weak var visaIV: UIImageView!
    @IBOutlet weak var visaTintV: UIView!
    @IBOutlet weak var visaLbl: UILabel!
    
    override func layoutIfNeeded() {

        contentView.frame = CGRect(x: 16, y: 5, width: contentView.frame.width - 32, height: 140)
        
        visaIV.layer.cornerRadius = 30
        visaIV.layer.masksToBounds = true
        
        visaTintV.layer.cornerRadius = 30
        visaTintV.layer.masksToBounds = true
    }


}
