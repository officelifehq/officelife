describe('Team - Recent ships management', function () {
  it('should let you manage recent ship as an administrator', function () {
    cy.loginLegacy()

    cy.createCompany()
    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'admin', false)
    cy.createTeam('product')

    cy.wait(1000)

    cy.visit('/1/teams/1')
    cy.get('[data-cy=recent-ships-list-blank-state]').should('exist')

    // create an entry with only title
    cy.createRecentShip('Feature 1 shipped')

    // check to see if the data is there in the table
    cy.get('[data-cy=ships-list-1]').contains('Feature 1 shipped')
    cy.hasAuditLog('Created a recent ship entry called Feature 1 shipped', '/1/teams/1')
    cy.hasTeamLog('Created a new recent ship called Feature 1 shipped', '/1/teams/1')

    // create an entry with title+description
    cy.createRecentShip('Feature 2 shipped', 'description')

    // check to see if the data is there in the table
    cy.get('[data-cy=ships-list-2]').contains('Feature 2 shipped')
    cy.hasAuditLog('Created a recent ship entry called Feature 2 shipped', '/1/teams/1')
    cy.hasTeamLog('Created a new recent ship called Feature 2 shipped', '/1/teams/1')

    // create an entry with title+description+employees
    cy.createRecentShip('Feature 3 shipped', 'awesome feature', 'michael', 2)

    // check to see if the data is there in the table
    cy.get('[data-cy=ships-list-3]').contains('Feature 3 shipped')
    cy.get('[data-cy=ship-list-3-avatar-2]').should('exist')
    cy.hasAuditLog('Created a recent ship entry called Feature 3 shipped', '/1/teams/1')
    cy.hasTeamLog('Created a new recent ship called Feature 3 shipped', '/1/teams/1')

    // delete a recent ship entry
    cy.visit('/1/teams/1/ships/3')
    cy.get('[data-cy=list-delete-button-3]').click()
    cy.get('[data-cy=list-delete-cancel-button-3]').click()
    cy.get('[data-cy=list-delete-button-3]').click()
    cy.get('[data-cy=list-delete-confirm-button-3]').click()
    cy.hasAuditLog('Deleted the recent ship entry called', '/1/teams/1')
    cy.hasTeamLog('Deleted the recent ship', '/1/teams/1')
  })

  it('should let you manage recent ship as an HR', function () {
    cy.loginLegacy()

    cy.createCompany()
    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'admin', false)
    cy.createTeam('product')

    cy.changePermission(1, 200)

    cy.wait(1000)

    cy.visit('/1/teams/1')
    cy.get('[data-cy=recent-ships-list-blank-state]').should('exist')

    // create an entry with only title
    cy.createRecentShip('Feature 1 shipped')

    // check to see if the data is there in the table
    cy.get('[data-cy=ships-list-1]').contains('Feature 1 shipped')

    // create an entry with title+description
    cy.createRecentShip('Feature 2 shipped', 'awesome feature')

    // check to see if the data is there in the table
    cy.get('[data-cy=ships-list-2]').contains('Feature 2 shipped')

    // create an entry with title+description+employees
    cy.createRecentShip('Feature 3 shipped', 'awesome feature', 'michael', 2)

    // check to see if the data is there in the table
    cy.get('[data-cy=ships-list-3]').contains('Feature 3 shipped')
    cy.get('[data-cy=ship-list-3-avatar-2]').should('exist')

    // delete a recent ship entry
    cy.visit('/1/teams/1/ships/3')
    cy.get('[data-cy=list-delete-button-3]').click()
    cy.get('[data-cy=list-delete-cancel-button-3]').click()
    cy.get('[data-cy=list-delete-button-3]').click()
    cy.get('[data-cy=list-delete-confirm-button-3]').click()
  })

  it('should let you manage recent ship as a normal user who is part of the team', function () {
    cy.loginLegacy()

    cy.createCompany()
    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'admin', false)
    cy.createTeam('product')

    // add admin to the team so he can manage the team
    cy.visit('/1/teams/1')
    cy.get('[data-cy=manage-team-on]').click()
    cy.get('[data-cy=member-input]').type('admin@admin.com')
    cy.wait(600)
    cy.get('[data-cy=employee-id-1]').click()
    cy.visit('/1/teams/1')

    cy.changePermission(1, 300)

    cy.wait(1000)

    cy.visit('/1/teams/1')
    cy.get('[data-cy=recent-ships-list-blank-state]').should('exist')

    // create an entry with only title
    cy.createRecentShip('Feature 1 shipped')

    // check to see if the data is there in the table
    cy.get('[data-cy=ships-list-1]').contains('Feature 1 shipped')

    // create an entry with title+description
    cy.createRecentShip('Feature 2 shipped', 'awesome feature')

    // check to see if the data is there in the table
    cy.get('[data-cy=ships-list-2]').contains('Feature 2 shipped')

    // create an entry with title+description+employees
    cy.createRecentShip('Feature 3 shipped', 'awesome feature', 'michael', 2)

    // check to see if the data is there in the table
    cy.get('[data-cy=ships-list-3]').contains('Feature 3 shipped')
    cy.get('[data-cy=ship-list-3-avatar-2]').should('exist')

    // delete a recent ship entry
    cy.visit('/1/teams/1/ships/3')
    cy.get('[data-cy=list-delete-button-3]').should('not.exist')
  })

  it('should not let you manage recent ships as a normal user who is not part of the team', function () {
    cy.loginLegacy()

    cy.createCompany()
    cy.createTeam('product')

    cy.wait(1000)

    cy.changePermission(1, 300)

    cy.visit('/1/teams/1')

    // open the new page
    cy.get('[data-cy=add-recent-ship-entry]').should('not.exist')
  })

  it('should let a normal user consult the details of a recent ship entry from all the places a recent ship entry is listed', function () {
    cy.loginLegacy()

    cy.createCompany()
    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'admin', false)
    cy.createTeam('product')

    // add admin to the team so he can manage the team
    cy.visit('/1/teams/1')
    cy.get('[data-cy=manage-team-on]').click()
    cy.get('[data-cy=member-input]').type('admin@admin.com')
    cy.wait(600)
    cy.get('[data-cy=employee-id-1]').click()
    cy.visit('/1/teams/1')

    cy.changePermission(1, 300)

    cy.wait(1000)

    cy.visit('/1/teams/1')

    // create an entry with title+description+employees
    cy.createRecentShip('Feature 3 shipped', 'awesome feature', 'michael', 2)

    // visit the recent ship entry from the team page
    cy.visit('/1/teams/1')
    cy.get('[data-cy=recent-ship-list-1]').click()
    cy.readRecentShipEntry('Feature 3 shipped', 'awesome feature', 'Michael', 2)

    // visit the recent ship entry from the employee page
    cy.visit('/1/employees/2')
    cy.get('[data-cy=ship-list-item-1]').click()
    cy.readRecentShipEntry('Feature 3 shipped', 'awesome feature', 'Michael', 2)

    // visit the recent ship entry from the dashboard team page
    cy.visit('/1/dashboard/team')
    cy.get('[data-cy=ship-list-item-1]').click()
    cy.readRecentShipEntry('Feature 3 shipped', 'awesome feature', 'Michael', 2)
  })

  it('should create a notification when you are associated with a recent ship', function () {
    cy.loginLegacy()

    cy.createCompany()
    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'admin', false)
    cy.createTeam('product')

    // add admin to the team so he can manage the team
    cy.visit('/1/teams/1')
    cy.get('[data-cy=manage-team-on]').click()
    cy.get('[data-cy=member-input]').type('admin@admin.com')
    cy.wait(600)
    cy.get('[data-cy=employee-id-1]').click()
    cy.visit('/1/teams/1')

    cy.changePermission(1, 300)

    cy.wait(1000)

    cy.visit('/1/teams/1')

    // create an entry with title+description+employees
    cy.createRecentShip('Feature 3 shipped', 'awesome feature', 'admin', 1)

    // check to see if the data is there in the table
    cy.visit('/1/teams/1')
    cy.get('[data-cy=notification-counter]').contains('2')
    cy.get('[data-cy=notification-counter]').click()

    cy.hasNotification('You have been associated with the recent ship called Feature 3 shipped.')

  })
})
