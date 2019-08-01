describe('Worklogs - teams', function () {
  it('should display an empty tab when not associated with a team', function () {
    cy.login()

    cy.createCompany()

    cy.get('[data-cy=dashboard-team-tab]').click()

    cy.contains('You are not associated with a team yet')
  })

  it('should display the list of teams if the enployee is associated with at least one team', function () {
    cy.login()

    cy.createCompany()

    cy.createTeam('product')
    cy.createTeam('sales')

    // add a first team
    cy.visit('/1/employees/1')

    cy.get('[data-cy=open-team-modal-blank]').click()
    cy.get('[data-cy=list-team-1]').click()

    cy.visit('/1/dashboard')

    cy.get('[data-cy=dashboard-team-tab]').click()

    cy.get('body').should('not.contain', 'What your team has done this week')

    // add a second team
    cy.visit('/1/employees/1')
    cy.get('[data-cy=open-team-modal]').click()
    cy.get('[data-cy=list-team-2]').click()

    cy.visit('/1/dashboard')

    cy.get('[data-cy=dashboard-team-tab]').click()

    cy.contains('What your team has done this week')
    cy.contains('Viewing')
    cy.contains('product')
    cy.contains('sales')
  })
})
