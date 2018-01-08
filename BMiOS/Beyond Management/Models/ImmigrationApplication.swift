//
//  ImmigrationApplication.swift
//  Beyond Management
//
//  Created by Mamdouh El Nakeeb on 1/6/18.
//  Copyright © 2018 Beyond Management. All rights reserved.
//

import Foundation

class ImmigrationApplication {
    
    var id = 0
    var visa = ""
    var type = ""
    var status = ""
    var img = ""
    
    init(id: Int, visa: String, type: String, status: String, img: String) {
        self.id = id
        self.visa = visa
        self.type = type
        self.status = status
        self.img = img
    }
    
    init(){
        
    }
}
