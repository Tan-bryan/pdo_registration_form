CREATE TABLE users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,   -- Primary key for the user
    firstname VARCHAR(100) NOT NULL,         -- First name of the user
    lastname VARCHAR(100) NOT NULL,          -- Last name of the user
    address TEXT NOT NULL,                   -- Address of the user
    email VARCHAR(150) NOT NULL UNIQUE,      -- Email (unique to each user)
    dob DATE NOT NULL,                       -- Date of birth
    gender ENUM('Male', 'Female', 'Other') NOT NULL,  -- Gender with predefined options
    citizenship VARCHAR(100) NOT NULL,       -- Citizenship or nationality
    position VARCHAR(100) NOT NULL,          -- Position the user is applying for
    experience INT(3) NOT NULL,              -- Number of years of experience
    about TEXT NOT NULL,                     -- Text area where the user describes themselves
    password VARCHAR(255) NOT NULL,          -- Hashed password for security
    date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- Timestamp when the record was created
);