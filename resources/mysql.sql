-- #! mysql

-- #{ nickui

-- #  { init
CREATE TABLE IF NOT EXISTS nickNames (
    playerName VARCHAR(255) PRIMARY KEY,
    displayName VARCHAR(255),
    nickName VARCHAR(255),
    nickNames TEXT
);
-- #  }

-- #  { create_account
-- #    :playerName string
-- #    :displayName string
-- #    :nickName string
-- #    :nickNames string
INSERT INTO nickNames (playerName, displayName, nickName, nickNames) VALUES (:playerName, :displayName, :nickName, :nickNames);
-- #  }

-- # { check_existence
-- #    :playerName string
SELECT COUNT(*) as count FROM nickNames WHERE playerName = :playerName;
-- # }

-- #  { get_nickName
-- #    :playerName string
SELECT nickName FROM nickNames WHERE playerName = :playerName;
-- #  }

-- #  { get_displayName
-- #    :playerName string
SELECT displayName FROM nickNames WHERE playerName = :playerName;
-- #  }

-- #  { get_all_nickNames
-- #    :playerName string
SELECT nickNames FROM nickNames WHERE playerName = :playerName;
-- #  }

-- #  { update_nicknames
-- #    :playerName string
-- #    :nickNames string
UPDATE nickNames SET nickNames = :nickNames WHERE playerName = :playerName;
-- #  }

-- #  { set_nickName
-- #    :playerName string
-- #    :nickName string
UPDATE nickNames SET nickName = :nickName WHERE playerName = :playerName;
-- #  }

-- #}